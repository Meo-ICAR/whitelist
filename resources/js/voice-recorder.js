// Registratore vocale in-browser con alteratore del timbro, per il form
// pubblico di segnalazione. Registra dal microfono, altera il tono
// dell'audio (per non rendere riconoscibile la voce del segnalante) e
// allega il risultato come prova, tramite l'upload nativo di Livewire.
//
// L'alterazione del timbro è una tecnica di "resampling": il segnale viene
// ricostruito a una velocità di riproduzione diversa (qui più lenta, per
// abbassare il tono e mascherare meglio le voci femminili/acute), il che
// ne cambia sia l'altezza percepita sia la durata. Non è preservazione dei
// formanti di livello professionale, ma è sufficiente come primo livello di
// protezione dell'anonimato ed è realizzabile senza librerie esterne.
const PITCH_SHIFT_RATE = 0.82;

function encodeWav(audioBuffer) {
    const numChannels = audioBuffer.numberOfChannels;
    const sampleRate = audioBuffer.sampleRate;
    const numFrames = audioBuffer.length;
    const bytesPerSample = 2;
    const blockAlign = numChannels * bytesPerSample;
    const dataSize = numFrames * blockAlign;

    const buffer = new ArrayBuffer(44 + dataSize);
    const view = new DataView(buffer);

    const writeString = (offset, str) => {
        for (let i = 0; i < str.length; i++) {
            view.setUint8(offset + i, str.charCodeAt(i));
        }
    };

    writeString(0, 'RIFF');
    view.setUint32(4, 36 + dataSize, true);
    writeString(8, 'WAVE');
    writeString(12, 'fmt ');
    view.setUint32(16, 16, true);
    view.setUint16(20, 1, true); // PCM
    view.setUint16(22, numChannels, true);
    view.setUint32(24, sampleRate, true);
    view.setUint32(28, sampleRate * blockAlign, true);
    view.setUint16(32, blockAlign, true);
    view.setUint16(34, bytesPerSample * 8, true);
    writeString(36, 'data');
    view.setUint32(40, dataSize, true);

    const channels = [];
    for (let c = 0; c < numChannels; c++) {
        channels.push(audioBuffer.getChannelData(c));
    }

    let offset = 44;
    for (let i = 0; i < numFrames; i++) {
        for (let c = 0; c < numChannels; c++) {
            const sample = Math.max(-1, Math.min(1, channels[c][i]));
            view.setInt16(offset, sample < 0 ? sample * 0x8000 : sample * 0x7fff, true);
            offset += 2;
        }
    }

    return new Blob([buffer], { type: 'audio/wav' });
}

async function alterPitch(blob) {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    const audioContext = new AudioContextClass();
    const arrayBuffer = await blob.arrayBuffer();
    const decoded = await audioContext.decodeAudioData(arrayBuffer);

    const offlineContext = new OfflineAudioContext(
        decoded.numberOfChannels,
        Math.ceil(decoded.length / PITCH_SHIFT_RATE),
        decoded.sampleRate
    );

    const source = offlineContext.createBufferSource();
    source.buffer = decoded;
    source.playbackRate.value = PITCH_SHIFT_RATE;
    source.connect(offlineContext.destination);
    source.start();

    const rendered = await offlineContext.startRendering();
    await audioContext.close();

    return encodeWav(rendered);
}

document.addEventListener('alpine:init', () => {
    Alpine.data('voiceRecorder', () => ({
        isRecording: false,
        status: '',
        previewUrl: null,
        elapsed: 0,
        mediaRecorder: null,
        chunks: [],
        timerId: null,

        async startRecording() {
            this.status = '';
            this.previewUrl = null;

            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.chunks = [];
                this.mediaRecorder = new MediaRecorder(stream);

                this.mediaRecorder.ondataavailable = (event) => {
                    if (event.data.size > 0) {
                        this.chunks.push(event.data);
                    }
                };

                this.mediaRecorder.onstop = () => {
                    stream.getTracks().forEach((track) => track.stop());
                    this.processRecording();
                };

                this.mediaRecorder.start();
                this.isRecording = true;
                this.elapsed = 0;
                this.timerId = setInterval(() => { this.elapsed++; }, 1000);
            } catch (error) {
                this.status = 'Impossibile accedere al microfono. Verifica i permessi del browser.';
            }
        },

        stopRecording() {
            if (this.mediaRecorder && this.isRecording) {
                this.mediaRecorder.stop();
                this.isRecording = false;
                clearInterval(this.timerId);
            }
        },

        async processRecording() {
            this.status = 'Alterazione del timbro vocale in corso...';

            try {
                const rawBlob = new Blob(this.chunks, { type: this.mediaRecorder.mimeType || 'audio/webm' });
                const alteredBlob = await alterPitch(rawBlob);

                this.previewUrl = URL.createObjectURL(alteredBlob);

                const fileName = `messaggio-vocale-${Date.now()}.wav`;
                const file = new File([alteredBlob], fileName, { type: 'audio/wav' });

                this.status = 'Allegato in corso...';

                this.$wire.upload(
                    'data.attachments',
                    file,
                    () => { this.status = 'Messaggio vocale allegato alla segnalazione.'; },
                    () => { this.status = 'Errore durante il caricamento del messaggio vocale.'; },
                );
            } catch (error) {
                this.status = 'Impossibile elaborare la registrazione. Riprova.';
            }
        },
    }));
});
