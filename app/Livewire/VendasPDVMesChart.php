<?php

namespace App\Livewire;

use App\Models\VendaPDV;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class VendasPDVMesChart extends ChartWidget
{
    protected static ?string $heading = 'Vendas Mensal - PDV';

    protected static ?int $sort = 4;

    // Ajuste de layout: Define a largura do widget no painel (ex: full, 1/2, 1/3)
   // protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // O método 'dateColumn' indica qual campo do banco usar em vez do 'created_at'
        $data = Trend::model(VendaPDV::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->dateColumn('data_venda') // Define o campo de data solicitado
            ->perMonth()
            ->sum('valor_total_desconto'); // Define o campo de valor solicitado
        

        return [
            'datasets' => [
                [
                    'label' => 'Total de Vendas (R$)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    // Melhoria visual: suaviza a linha e adiciona cor
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            // Formata a data para exibir o nome do mês em português ou formato legível
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}