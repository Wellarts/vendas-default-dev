<?php

namespace App\Livewire;

use App\Models\Venda;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TotalVendaStatsOverview extends BaseWidget
{
    // O record é opcional, permitindo que o widget funcione no Dashboard ou em páginas de Venda
    public ?Model $record = null;

    protected function getStats(): array
    {
        $hoje = Carbon::now()->toDateString();
        $mes = Carbon::now()->month;
        $ano = Carbon::now()->year;

        // 1. Total Geral (Sem filtro de data)
        $totalGeral = Cache::remember('vendas_total_geral', now()->addMinutes(30), function () {
            return DB::table('venda_p_d_v_s')->sum('valor_total_desconto') ?? 0;
        });

        // 2. Vendas Hoje (Filtrado por data_venda)
        $totalHoje = Cache::remember("vendas_hoje_{$hoje}", now()->addMinutes(5), function () use ($hoje) {
            return DB::table('venda_p_d_v_s')
                ->whereDate('data_venda', $hoje)
                ->sum('valor_total_desconto') ?? 0;
        });

        // 3. Vendas no Mês (Filtrado por data_venda)
        $totalMes = Cache::remember("vendas_mes_{$ano}_{$mes}", now()->addMinutes(10), function () use ($mes, $ano) {
            return DB::table('venda_p_d_v_s')
                ->whereMonth('data_venda', $mes)
                ->whereYear('data_venda', $ano)
                ->sum('valor_total_desconto') ?? 0;
        });

        $formatar = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        return [
            Stat::make('Vendas Hoje', $formatar($totalHoje))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart($this->getTrendUltimos7Dias())
                ->color('success'),

            Stat::make('Vendas este Mês', $formatar($totalMes))
                ->description('Mês atual')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make('Venda Total Acumulada', $formatar($totalGeral))
                ->description('Todo o período')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    /**
     * Gera a linha do gráfico baseada no campo data_venda
     */
    private function getTrendUltimos7Dias(): array
    {
        return Cache::remember('vendas_trend_7d_dash', now()->addMinutes(15), function () {
            $dados = [];
            for ($i = 6; $i >= 0; $i--) {
                $data = Carbon::now()->subDays($i)->toDateString();
                $dados[] = DB::table('vendas')
                    ->whereDate('data_venda', $data)
                    ->sum('valor_total_desconto') ?? 0;
            }
            return $dados;
        });
    }
}