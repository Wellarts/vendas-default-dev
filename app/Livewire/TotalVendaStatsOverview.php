<?php

namespace App\Livewire;

use App\Models\Venda;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        $totalGeral = DB::table('venda_p_d_v_s')->sum('valor_total_desconto') ?? 0;

        // 2. Vendas Hoje (Filtrado por data_venda)
        $totalHoje = DB::table('venda_p_d_v_s')
            ->whereDate('data_venda', $hoje)
            ->sum('valor_total_desconto') ?? 0;

        // 3. Vendas no Mês (Filtrado por data_venda)
        $totalMes = DB::table('venda_p_d_v_s')
            ->whereMonth('data_venda', $mes)
            ->whereYear('data_venda', $ano)
            ->sum('valor_total_desconto') ?? 0;

        $formatar = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        return [
            Stat::make('Vendas Hoje', $formatar($totalHoje))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart($this->getTrendUltimos7Dias())
                ->color('success'),

            Stat::make('Vendas este Mês', $formatar($totalMes))
                ->description('Este Mês')
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
        $dados = [];
        for ($i = 6; $i >= 0; $i--) {
            $data = Carbon::now()->subDays($i)->toDateString();
            $valor = DB::table('venda_p_d_v_s') // Corrigido: usar a mesma tabela 'venda_p_d_v_s'
                ->whereDate('data_venda', $data)
                ->sum('valor_total_desconto') ?? 0;
            
            $dados[] = round($valor, 2);
        }
        return $dados;
    }
}