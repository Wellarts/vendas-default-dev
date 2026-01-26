<?php

namespace App\Livewire;

use App\Models\VwTotalVendasPorCliente;
use Filament\Widgets\ChartWidget;

class TotalVendasPorCliente extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Clientes (Faturamento)';

    protected static ?int $sort = 5;

    // Define uma largura maior para acomodar nomes de clientes longos
   // protected int | string | array $columnSpan = 'full';

    // Altura do gráfico para não ficar muito "achatado" em telas grandes
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Realizamos apenas uma query para obter nomes e valores simultaneamente
        $vendasPorCliente = VwTotalVendasPorCliente::query()
            ->select('cliente_nome', 'valor_total_desconto')
            ->orderByDesc('valor_total_desconto')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Comprado (R$)',
                    'data'  => $vendasPorCliente->pluck('valor_total_desconto')->toArray(),
                    // Cores modernas com gradiente sólido do Tailwind/Filament
                    'backgroundColor' => [
                        '#3b82f6', // blue-500
                        '#10b981', // emerald-500
                        '#f59e0b', // amber-500
                        '#ef4444', // red-500
                        '#8b5cf6', // violet-500
                        '#ec4899', // pink-500
                        '#06b6d4', // cyan-500
                        '#f97316', // orange-500
                        '#84cc16', // lime-500
                        '#6366f1', // indigo-500
                    ],
                    'borderRadius' => 4, // Bordas arredondadas nas barras
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $vendasPorCliente->pluck('cliente_nome')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Inverte para barras horizontais (melhor para ler nomes)
            'plugins' => [
                'legend' => [
                    'display' => false, // Remove a legenda repetitiva
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'grid' => ['display' => false],
                ],
            ],
        ];
    }
}