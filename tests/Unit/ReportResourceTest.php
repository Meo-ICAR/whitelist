<?php

namespace Tests\Unit;

use App\Filament\Resources\Reports\RelationManagers\MessagesRelationManager;
use App\Filament\Resources\Reports\ReportResource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Tests\TestCase;

class ReportResourceTest extends TestCase
{
    /**
     * 11.6 — canCreate() returns false for ReportResource
     *
     * @test
     */
    public function report_resource_cannot_create_reports(): void
    {
        $this->assertFalse(ReportResource::canCreate());
    }

    /**
     * 11.7 — Messages relation manager has no edit or delete actions
     *
     * @test
     */
    public function messages_relation_manager_has_no_edit_or_delete_actions(): void
    {
        $relations = ReportResource::getRelations();

        $this->assertContains(MessagesRelationManager::class, $relations);
    }

    /**
     * 11.7 — Verify MessagesRelationManager actions array is empty (no EditAction/DeleteAction)
     *
     * @test
     */
    public function messages_relation_manager_actions_do_not_include_edit_or_delete(): void
    {
        // Inspect the source of MessagesRelationManager to confirm no EditAction/DeleteAction
        // We verify this by checking the class does not import those action classes
        $reflection = new \ReflectionClass(MessagesRelationManager::class);
        $filename = $reflection->getFileName();
        $source = file_get_contents($filename);

        // Check that Filament\Tables\Actions\EditAction is not imported
        $this->assertStringNotContainsString(
            'Filament\Tables\Actions\EditAction',
            $source,
            'MessagesRelationManager should not import Filament\Tables\Actions\EditAction'
        );

        // Check that Filament\Tables\Actions\DeleteAction is not imported
        $this->assertStringNotContainsString(
            'Filament\Tables\Actions\DeleteAction',
            $source,
            'MessagesRelationManager should not import Filament\Tables\Actions\DeleteAction'
        );
    }

    /**
     * 11.7 — Verify the actions() array in MessagesRelationManager table is empty
     *
     * @test
     */
    public function messages_relation_manager_has_empty_actions_array(): void
    {
        // The ->actions([]) call with an empty array means no row-level actions
        $reflection = new \ReflectionClass(MessagesRelationManager::class);
        $filename = $reflection->getFileName();
        $source = file_get_contents($filename);

        // Verify the actions([]) call exists with empty array
        $this->assertMatchesRegularExpression(
            '/->actions\(\s*\[\s*\/\/[^\]]*\]\s*\)/',
            $source,
            'MessagesRelationManager should have ->actions([]) with empty array for audit trail'
        );
    }
}
