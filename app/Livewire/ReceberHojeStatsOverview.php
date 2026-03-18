<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReceberHojeStatsOverview extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $hoje = $now->toDateString(); // Formato YYYY-MM-DD

        // Busca dados diretamente do banco
        $totalHoje = DB::table('contas_recebers')
            ->where('status', 0)
            ->whereDate('data_vencimento', $hoje) // Corrigido: compara data completa
            ->sum('valor_parcela') ?? 0;

        $totalMes = DB::table('contas_recebers')
            ->where('status', 0)
            ->whereYear('data_vencimento', $now->year)
            ->whereMonth('data_vencimento', $now->month)
            ->sum('valor_parcela') ?? 0;

        $totalGeral = DB::table('contas_recebers')
            ->where('status', 0)
            ->sum('valor_parcela') ?? 0;

        // Formatador reutilizável
        $formatarValor = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        return [
            Stat::make('A Receber Hoje', $formatarValor($totalHoje))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger')
                ->chart($this->gerarDadosHoje())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('A Receber Este Mês', $formatarValor($totalMes))
                ->description('Este Mês')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->chart($this->gerarDadosMes())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('Tudo a Receber', $formatarValor($totalGeral))
                ->description('Total geral')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($this->gerarDadosGeral())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    /**
     * Gera dados dos últimos 7 dias para o gráfico
     */
    private function gerarDadosHoje(): array
    {
        $dados = [];
        
        // Últimos 7 dias
        for ($i = 6; $i >= 0; $i--) {
            $data = Carbon::now()->subDays($i);
            $valor = DB::table('contas_recebers')
                ->where('status', 0)
                ->whereDate('data_vencimento', $data->toDateString())
                ->sum('valor_parcela') ?? 0;
            
            $dados[] = round($valor, 2);
        }
        
        return $dados;
    }

    /**
     * Gera dados diários do mês atual para o gráfico
     */
    private function gerarDadosMes(): array
    {
        $now = Carbon::now();
        $diasNoMes = $now->daysInMonth;
        $diasTranscorridos = min($diasNoMes, $now->day);
        $dados = [];
        
        for ($dia = 1; $dia <= $diasTranscorridos; $dia++) {
            $data = Carbon::create($now->year, $now->month, $dia);
            $valor = DB::table('contas_recebers')
                ->where('status', 0)
                ->whereDate('data_vencimento', $data->toDateString())
                ->sum('valor_parcela') ?? 0;
            
            $dados[] = round($valor, 2);
        }
        
        return $dados;
    }

    /**
     * Gera dados dos últimos 12 meses para o gráfico
     */
    private function gerarDadosGeral(): array
    {
        $dados = [];
        
        // Últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $data = Carbon::now()->subMonths($i);
            $valor = DB::table('contas_recebers')
                ->where('status', 0)
                ->whereYear('data_vencimento', $data->year)
                ->whereMonth('data_vencimento', $data->month)
                ->sum('valor_parcela') ?? 0;
            
            $dados[] = round($valor, 2);
        }
        
        return $dados;
    }
}