<?php

namespace App\Livewire;

use App\Models\VendaPDV;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class VendasPDVMesChart extends ChartWidget
{
    protected static ?string $heading = 'Vendas Mensais - PDV';
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $anoAtual = now()->year;
        $cacheKey = 'vendas_pdv_mensal_' . $anoAtual;
        
        $dados = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($anoAtual) {
            $vendas = VendaPDV::selectRaw('MONTH(created_at) as mes, SUM(valor_total_desconto) as total')
                ->where('tipo_registro', 'VENDA')
                ->whereYear('created_at', $anoAtual)
                ->groupByRaw('MONTH(created_at)')
                ->orderByRaw('MONTH(created_at)')
                ->get();
            
            // Inicializa array com 12 meses zerados
            $dadosMensais = array_fill(0, 12, 0);
            
            // Preenche com os dados reais
            foreach ($vendas as $venda) {
                $dadosMensais[$venda->mes - 1] = (float) $venda->total;
            }
            
            return $dadosMensais;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Vendas ' . $anoAtual,
                    'data' => $dados,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => true,
            'plugins' => {
                'legend' => {
                    'position' => 'top',
                },
                'tooltip' => {
                    'mode' => 'index',
                    'intersect' => false,
                    'callbacks' => {
                        'label' => "function(context) {
                            return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }"
                    }
                }
            },
            'scales' => {
                'x' => {
                    'grid' => {
                        'display' => false,
                    }
                },
                'y' => {
                    'beginAtZero' => true,
                    'grid' => {
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    },
                    'ticks' => {
                        'callback' => "function(value) {
                            if (value >= 1000000) {
                                return 'R$ ' + (value / 1000000).toFixed(1).replace('.', ',') + 'M';
                            }
                            if (value >= 1000) {
                                return 'R$ ' + (value / 1000).toFixed(1).replace('.', ',') + 'k';
                            }
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }"
                    }
                }
            },
            'animation' => {
                'duration' => 1000,
            },
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        $anoAtual = now()->year;
        $cacheKey = 'vendas_pdv_total_' . $anoAtual;
        
        $totalAno = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($anoAtual) {
            return VendaPDV::where('tipo_registro', 'VENDA')
                ->whereYear('created_at', $anoAtual)
                ->sum('valor_total_desconto') ?? 0;
        });
        
        $mesAtual = now()->month;
        $mesesTranscorridos = max(1, $mesAtual);
        $mediaMensal = $totalAno / $mesesTranscorridos;
        
        return sprintf(
            'Total: R$ %s | Média: R$ %s',
            number_format($totalAno, 2, ',', '.'),
            number_format($mediaMensal, 2, ',', '.')
        );
    }

    // Método para limpar cache quando necessário
    public static function clearCache(): void
    {
        $anoAtual = now()->year;
        Cache::forget('vendas_pdv_mensal_' . $anoAtual);
        Cache::forget('vendas_pdv_total_' . $anoAtual);
    }

    // Método para invalidar cache quando nova venda é registrada
    public static function invalidateCacheOnNewVenda(): void
    {
        self::clearCache();
    }
}