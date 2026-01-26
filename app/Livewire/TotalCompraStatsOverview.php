<?php

namespace App\Livewire;

use App\Models\Compra;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TotalCompraStatsOverview extends BaseWidget
{
    // Record opcional para funcionar tanto em Dashboards quanto em Visualização de Compra
    public $record;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Se houver um registro específico ($record), foca nos dados dessa compra
        if ($this->record) {
            return $this->getIndividualCompraStats();
        }

        return $this->getGeneralCompraStats();
    }

    protected function getIndividualCompraStats(): array
    {
        $compraId = $this->record->id;
        
        $stats = Cache::remember("compra_stats_id_{$compraId}", now()->addMinutes(5), function () use ($compraId) {
            return [
                'qtd' => DB::table('itens_compras')->where('compra_id', $compraId)->sum('qtd') ?? 0,
                'total' => DB::table('compras')->where('id', $compraId)->value('valor_total') ?? 0,
            ];
        });

        return [
            Stat::make('Itens na Compra', number_format($stats['qtd'], 2, ',', '.'))
                ->description('Quantidade total de itens')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Valor Total da Compra', 'R$ ' . number_format($stats['total'], 2, ',', '.'))
                ->description('Valor bruto da nota')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }

    protected function getGeneralCompraStats(): array
    {
        $hoje = Carbon::now()->toDateString();
        $mes = Carbon::now()->month;

        // Compras efetuadas hoje
        $totalHoje = Cache::remember("compras_total_hoje_{$hoje}", now()->addMinutes(5), function () use ($hoje) {
            return DB::table('compras')
                ->whereDate('data_compra', $hoje) // Ajustado para seu campo de data
                ->sum('valor_total') ?? 0;
        });

        // Compras acumuladas no mês
        $totalMes = Cache::remember("compras_total_mes_{$mes}", now()->addMinutes(10), function () use ($mes) {
            return DB::table('compras')
                ->whereMonth('data_compra', $mes)
                ->whereYear('data_compra', Carbon::now()->year)
                ->sum('valor_total') ?? 0;
        });

        $formatar = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        return [
            Stat::make('Compras Hoje', $formatar($totalHoje))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart($this->getTrendSeteDias())
                ->color('danger'),

            Stat::make('Compras no Mês', $formatar($totalMes))
                ->description('Mês atual')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),
            Stat::make('Total de Compras', $formatar(DB::table('compras')->sum('valor_total') ?? 0))
                ->description('Todo período')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
        ];
    }

    private function getTrendSeteDias(): array
    {
        return Cache::remember('compras_trend_7d', now()->addMinutes(15), function () {
            $dados = [];
            for ($i = 6; $i >= 0; $i--) {
                $data = Carbon::now()->subDays($i)->toDateString();
                $dados[] = DB::table('compras')
                    ->whereDate('data_compra', $data)
                    ->sum('valor_total') ?? 0;
            }
            return $dados;
        });
    }
}