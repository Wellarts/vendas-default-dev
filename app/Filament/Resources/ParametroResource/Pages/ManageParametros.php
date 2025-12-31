<?php

namespace App\Filament\Resources\ParametroResource\Pages;

use App\Filament\Resources\ParametroResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageParametros extends ManageRecords
{
    protected static string $resource = ParametroResource::class;

    protected static ?string $title = 'Parâmetros';    


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Adicionar Parâmetro')
                ->hidden(fn () => \App\Models\Parametro::count() >= 1)
                ->modalHeading('Adicionar Parâmetro'),
        ];
    }
}
