<?php

namespace App\Livewire;

use App\Models\FluxoCaixa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CaixaStatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        
        // Chaves de cache para melhor performance
        $cacheKeys = [
            'saldo_total' => "fluxo_caixa_saldo_total",
            'debitos_total' => "fluxo_caixa_debitos_total",
            'creditos_total' => "fluxo_caixa_creditos_total",
            'hoje_entradas' => "fluxo_caixa_hoje_entradas_{$today}",
            'hoje_saidas' => "fluxo_caixa_hoje_saidas_{$today}",
        ];

        // Busca dados com cache
        $saldoTotal = Cache::remember($cacheKeys['saldo_total'], now()->addMinutes(15), function () {
            return FluxoCaixa::sum('valor') ?? 0;
        });

        $debitosTotal = Cache::remember($cacheKeys['debitos_total'], now()->addMinutes(15), function () {
            return FluxoCaixa::where('valor', '<', 0)->sum('valor') ?? 0;
        });

        $creditosTotal = Cache::remember($cacheKeys['creditos_total'], now()->addMinutes(15), function () {
            return FluxoCaixa::where('valor', '>', 0)->sum('valor') ?? 0;
        });

        // Valores do dia atual
        $entradasHoje = Cache::remember($cacheKeys['hoje_entradas'], now()->addMinutes(5), function () use ($today) {
            return FluxoCaixa::where('valor', '>', 0)
                ->whereDate('created_at', $today)
                ->sum('valor') ?? 0;
        });

        $saidasHoje = Cache::remember($cacheKeys['hoje_saidas'], now()->addMinutes(5), function () use ($today) {
            return FluxoCaixa::where('valor', '<', 0)
                ->whereDate('created_at', $today)
                ->sum('valor') ?? 0;
        });

        // Calcula saldo do dia
        $saldoHoje = $entradasHoje + $saidasHoje; // saídas já são negativas

        // Formatador reutilizável
        $formatarValor = function ($valor, $incluirSimbolo = true) {
            $formatado = number_format(abs($valor), 2, ',', '.');
            $sinal = $valor < 0 ? '-' : '';
            $simbolo = $incluirSimbolo ? 'R$ ' : '';
            return $sinal . $simbolo . $formatado;
        };

        // Cores baseadas no valor
        $corSaldo = $saldoTotal >= 0 ? 'success' : 'danger';
        $corSaldoHoje = $saldoHoje >= 0 ? 'success' : 'danger';

        return [
            Stat::make('Saldo Total', $formatarValor($saldoTotal))
                ->description($saldoTotal >= 0 ? 'Positivo' : 'Negativo')
                ->descriptionIcon($saldoTotal >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($corSaldo)
                ->chart($this->gerarDadosHistorico(7))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                    'title' => 'Clique para ver detalhes',
                ]),

            Stat::make('Entradas (Créditos)', $formatarValor($creditosTotal))
                ->description("Hoje: " . $formatarValor($entradasHoje, false))
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->color('success')
                ->chart($this->gerarDadosEntradas(7))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('Saídas (Débitos)', $formatarValor(abs($debitosTotal)))
                ->description("Hoje: " . $formatarValor(abs($saidasHoje), false))
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('danger')
                ->chart($this->gerarDadosSaidas(7))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),

            Stat::make('Saldo do Dia', $formatarValor($saldoHoje))
                ->description($saldoHoje >= 0 ? 'Positivo hoje' : 'Negativo hoje')
                ->descriptionIcon('heroicon-m-calendar')
                ->color($corSaldoHoje)
                ->chart($this->gerarDadosDia())
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    /**
     * Gera dados históricos para gráfico
     */
    protected function gerarDadosHistorico(int $dias = 7): array
    {
        $cacheKey = "fluxo_caixa_historico_{$dias}_dias";
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dias) {
            $dados = [];
            $dataInicio = Carbon::now()->subDays($dias - 1)->startOfDay();
            
            for ($i = 0; $i < $dias; $i++) {
                $data = $dataInicio->copy()->addDays($i);
                $saldoDia = FluxoCaixa::whereDate('created_at', $data->toDateString())
                    ->sum('valor') ?? 0;
                
                $dados[] = round($saldoDia, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Gera dados de entradas para gráfico
     */
    protected function gerarDadosEntradas(int $dias = 7): array
    {
        $cacheKey = "fluxo_caixa_entradas_{$dias}_dias";
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dias) {
            $dados = [];
            $dataInicio = Carbon::now()->subDays($dias - 1)->startOfDay();
            
            for ($i = 0; $i < $dias; $i++) {
                $data = $dataInicio->copy()->addDays($i);
                $entradasDia = FluxoCaixa::where('valor', '>', 0)
                    ->whereDate('created_at', $data->toDateString())
                    ->sum('valor') ?? 0;
                
                $dados[] = round($entradasDia, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Gera dados de saídas para gráfico
     */
    protected function gerarDadosSaidas(int $dias = 7): array
    {
        $cacheKey = "fluxo_caixa_saidas_{$dias}_dias";
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dias) {
            $dados = [];
            $dataInicio = Carbon::now()->subDays($dias - 1)->startOfDay();
            
            for ($i = 0; $i < $dias; $i++) {
                $data = $dataInicio->copy()->addDays($i);
                $saidasDia = abs(FluxoCaixa::where('valor', '<', 0)
                    ->whereDate('created_at', $data->toDateString())
                    ->sum('valor') ?? 0);
                
                $dados[] = round($saidasDia, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Gera dados do dia (por hora) - CORRIGIDO
     */
    protected function gerarDadosDia(): array
    {
        $cacheKey = "fluxo_caixa_hoje_por_hora";
        $hoje = Carbon::now()->toDateString();
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($hoje) {
            $dados = [];
            
            for ($hora = 0; $hora < 24; $hora++) {
                // CORREÇÃO: Usando whereRaw com função HOUR do MySQL
                $saldoHora = FluxoCaixa::whereDate('created_at', $hoje)
                    ->whereRaw('HOUR(created_at) = ?', [$hora])
                    ->sum('valor') ?? 0;
                
                $dados[] = round($saldoHora, 2);
            }
            
            return $dados;
        });
    }

    // Método para limpar cache quando necessário
    public static function clearCache(): void
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        
        // Limpa caches principais
        Cache::forget("fluxo_caixa_saldo_total");
        Cache::forget("fluxo_caixa_debitos_total");
        Cache::forget("fluxo_caixa_creditos_total");
        Cache::forget("fluxo_caixa_hoje_entradas_{$today}");
        Cache::forget("fluxo_caixa_hoje_saidas_{$today}");
        
        // Limpa caches de gráficos
        Cache::forget("fluxo_caixa_historico_7_dias");
        Cache::forget("fluxo_caixa_entradas_7_dias");
        Cache::forget("fluxo_caixa_saidas_7_dias");
        Cache::forget("fluxo_caixa_hoje_por_hora");
    }

    // Método para invalidar cache quando novo fluxo é adicionado
    public static function invalidateCacheOnNewFluxo(): void
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        
        // Limpa caches do dia atual
        Cache::forget("fluxo_caixa_hoje_entradas_{$today}");
        Cache::forget("fluxo_caixa_hoje_saidas_{$today}");
        Cache::forget("fluxo_caixa_hoje_por_hora");
        
        // Limpa caches totais
        Cache::forget("fluxo_caixa_saldo_total");
        Cache::forget("fluxo_caixa_debitos_total");
        Cache::forget("fluxo_caixa_creditos_total");
        
        // Limpa caches de gráficos
        Cache::forget("fluxo_caixa_historico_7_dias");
        Cache::forget("fluxo_caixa_entradas_7_dias");
        Cache::forget("fluxo_caixa_saidas_7_dias");
    }
}