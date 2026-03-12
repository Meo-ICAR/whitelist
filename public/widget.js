// public/widget.js
(function() {
    // 1. Trova lo script tag che ha caricato questo file per estrarre i parametri
    var scripts = document.getElementsByTagName('script');
    var currentScript = scripts[scripts.length - 1];
    var companySlug = currentScript.getAttribute('data-company');

    // Sostituisci con il vero URL in produzione
    var baseUrl = 'https://tuoservizio.it';
    var iframeUrl = baseUrl + '/segnala/' + companySlug;

    // 2. Inietta il CSS per il modale e il pulsante
    var stili = document.createElement('style');
    stili.innerHTML = `
        #wb-floating-btn {
            position: fixed; bottom: 20px; right: 20px; z-index: 999999;
            background-color: #1d4ed8; color: white; padding: 12px 24px;
            border-radius: 50px; cursor: pointer; font-family: sans-serif;
            font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; transition: transform 0.2s;
        }
        #wb-floating-btn:hover { transform: scale(1.05); }
        #wb-modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); z-index: 9999999;
            align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }
        #wb-modal-content {
            background: white; width: 100%; max-width: 800px; height: 90vh;
            border-radius: 12px; overflow: hidden; position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column;
        }
        #wb-modal-header {
            background: #f3f4f6; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; font-family: sans-serif;
        }
        #wb-close-btn {
            background: none; border: none; font-size: 24px; cursor: pointer; color: #4b5563;
        }
        #wb-iframe {
            width: 100%; height: 100%; border: none; flex-grow: 1;
        }
    `;
    document.head.appendChild(stili);

    var baseUrl = 'https://whitelist.hassisto.eu';
    var formUrl = baseUrl + '/segnala/' + companySlug;
    var trackerUrl = baseUrl + '/tracker'; // L'URL della pagina Livewire del tracker

    // Aggiungiamo il CSS per il link secondario
    stili.innerHTML += `
        #wb-tracker-link {
            display: block; text-align: center; margin-top: 8px; color: #4b5563;
            font-size: 12px; cursor: pointer; text-decoration: underline; font-family: sans-serif;
            background: rgba(255,255,255,0.9); padding: 4px; border-radius: 4px;
        }
    `;

    // Creiamo il bottone e il link secondario
    var widgetHtml = `
        <div style="position: fixed; bottom: 20px; right: 20px; z-index: 999999; display: flex; flex-direction: column; align-items: flex-end;">
            <button id="wb-floating-btn">🛡️ Segnala Illecito</button>
            <span id="wb-tracker-link">Hai già un PIN? Controlla pratica</span>
        </div>

        <div id="wb-modal-overlay">
            <div id="wb-modal-content">
                <div id="wb-modal-header">
                    <span style="font-weight: bold; color: #374151;">Canale Sicuro di Segnalazione</span>
                    <button id="wb-close-btn">&times;</button>
                </div>
                <iframe id="wb-iframe" src="" allow="camera"></iframe>
            </div>
        </div>
    `;

    // Inserisci l'HTML nel body
    var container = document.createElement('div');
    container.innerHTML = widgetHtml;
    document.body.appendChild(container);

    // 4. Gestione degli Eventi (Apri/Chiudi)
  var btnOpenForm = document.getElementById('wb-floating-btn');
    var btnOpenTracker = document.getElementById('wb-tracker-link');
    var modal = document.getElementById('wb-modal-overlay');
    var iframe = document.getElementById('wb-iframe');

    // Se clicca su "Segnala" carica il form
    btnOpenForm.addEventListener('click', function() {
        iframe.src = formUrl;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    // Se clicca su "Controlla pratica" carica il tracker
    btnOpenTracker.addEventListener('click', function() {
        iframe.src = trackerUrl;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    btnOpen.addEventListener('click', function() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Blocca lo scroll della pagina sottostante
    });

    btnClose.addEventListener('click', function() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Sblocca lo scroll
    });

    // Chiudi se clicchi fuori dal modale
    modal.addEventListener('click', function(e) {
        if(e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
})();
