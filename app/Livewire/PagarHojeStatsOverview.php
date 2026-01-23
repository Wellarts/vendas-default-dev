<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PagarHojeStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $mes = $now->month;
        $dia = $now->day;
        $hoje = $now->toDateString();

        // Chaves de cache para melhor performance
        $cacheKeys = [
            'total_hoje' => "contas_pagar_total_hoje_{$hoje}",
            'total_mes' => "contas_pagar_total_mes_{$now->year}_{$mes}",
            'total_geral' => "contas_pagar_total_geral",
        ];

        // Busca dados com cache
        $totalHoje = Cache::remember($cacheKeys['total_hoje'], now()->addMinutes(5), function () use ($dia) {
            return DB::table('contas_pagars')
                ->where('status', 0)
                ->whereDay('data_vencimento', $dia)
                ->sum('valor_parcela') ?? 0;
        });

        $totalMes = Cache::remember($cacheKeys['total_mes'], now()->addMinutes(10), function () use ($mes) {
            return DB::table('contas_pagars')
                ->where('status', 0)
                ->whereMonth('data_vencimento', $mes)
                ->sum('valor_parcela') ?? 0;
        });

        $totalGeral = Cache::remember($cacheKeys['total_geral'], now()->addHour(), function () {
            return DB::table('contas_pagars')
                ->where('status', 0)
                ->sum('valor_parcela') ?? 0;
        });

        // Formatador reutilizável
        $formatarValor = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        return [
            Stat::make('A Pagar Hoje', $formatarValor($totalHoje))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('danger')
                ->chart($this->gerarDadosHoje())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('A Pagar Este Mês', $formatarValor($totalMes))
                ->description('Este mês')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->chart($this->gerarDadosMes())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('Tudo a Pagar', $formatarValor($totalGeral))
                ->description('Todo Período')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($this->gerarDadosGeral())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    /**
     * Gera dados de hoje para gráfico (últimos 7 dias)
     */
    private function gerarDadosHoje(): array
    {
        $cacheKey = 'contas_pagar_dados_hoje_7_dias';
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $dados = [];
            
            // Últimos 7 dias incluindo hoje
            for ($i = 6; $i >= 0; $i--) {
                $data = Carbon::now()->subDays($i);
                $valor = DB::table('contas_pagars')
                    ->where('status', 0)
                    ->whereDate('data_vencimento', $data->toDateString())
                    ->sum('valor_parcela') ?? 0;
                
                $dados[] = round($valor, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Gera dados do mês para gráfico (dias do mês atual)
     */
    private function gerarDadosMes(): array
    {
        $now = Carbon::now();
        $ano = $now->year;
        $mes = $now->month;
        $cacheKey = "contas_pagar_dados_mes_{$ano}_{$mes}";
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($ano, $mes) {
            $diasNoMes = Carbon::create($ano, $mes, 1)->daysInMonth;
            $diasTranscorridos = min($diasNoMes, Carbon::now()->day);
            $dados = [];
            
            for ($dia = 1; $dia <= $diasTranscorridos; $dia++) {
                $valor = DB::table('contas_pagars')
                    ->where('status', 0)
                    ->whereYear('data_vencimento', $ano)
                    ->whereMonth('data_vencimento', $mes)
                    ->whereDay('data_vencimento', $dia)
                    ->sum('valor_parcela') ?? 0;
                
                $dados[] = round($valor, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Gera dados gerais para gráfico (últimos 12 meses)
     */
    private function gerarDadosGeral(): array
    {
        $cacheKey = 'contas_pagar_dados_geral_12_meses';
        
        return Cache::remember($cacheKey, now()->addHour(), function () {
            $dados = [];
            
            // Últimos 12 meses
            for ($i = 11; $i >= 0; $i--) {
                $data = Carbon::now()->subMonths($i);
                $valor = DB::table('contas_pagars')
                    ->where('status', 0)
                    ->whereYear('data_vencimento', $data->year)
                    ->whereMonth('data_vencimento', $data->month)
                    ->sum('valor_parcela') ?? 0;
                
                $dados[] = round($valor, 2);
            }
            
            return $dados;
        });
    }

    // Método para limpar cache quando necessário
    public static function clearCache(): void
    {
        $now = Carbon::now();
        $hoje = $now->toDateString();
        
        Cache::forget("contas_pagar_total_hoje_{$hoje}");
        Cache::forget("contas_pagar_total_mes_{$now->year}_{$now->month}");
        Cache::forget("contas_pagar_total_geral");
        Cache::forget('contas_pagar_dados_hoje_7_dias');
        Cache::forget("contas_pagar_dados_mes_{$now->year}_{$now->month}");
        Cache::forget('contas_pagar_dados_geral_12_meses');
    }

    // Método para invalidar cache quando nova conta é criada ou atualizada
    public static function invalidateCacheOnChange(): void
    {
        $now = Carbon::now();
        $hoje = $now->toDateString();
        
        // Limpa cache do dia e mês atual
        Cache::forget("contas_pagar_total_hoje_{$hoje}");
        Cache::forget("contas_pagar_total_mes_{$now->year}_{$now->month}");
        
        // Limpa cache geral
        Cache::forget("contas_pagar_total_geral");
    }
}