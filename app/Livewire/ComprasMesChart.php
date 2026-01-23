<?php

namespace App\Livewire;

use App\Models\Compra;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ComprasMesChart extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        $now = Carbon::now();
        $anoAtual = $now->year;
        $mesAtual = $now->month;
        $hoje = $now->toDateString();
        
        // Chaves de cache para melhor performance
        $cacheKeys = [
            'total_geral' => "compras_total_geral",
            'total_mes' => "compras_total_mes_{$anoAtual}_{$mesAtual}",
            'total_hoje' => "compras_total_hoje_{$hoje}",
        ];

        // Busca dados com cache usando data_compra
        $totalGeral = Cache::remember($cacheKeys['total_geral'], now()->addHours(1), function () {
            return Compra::sum('valor_total') ?? 0;
        });

        $totalMes = Cache::remember($cacheKeys['total_mes'], now()->addMinutes(15), function () use ($anoAtual, $mesAtual) {
            return Compra::whereYear('data_compra', $anoAtual)
                ->whereMonth('data_compra', $mesAtual)
                ->sum('valor_total') ?? 0;
        });

        $totalHoje = Cache::remember($cacheKeys['total_hoje'], now()->addMinutes(5), function () use ($hoje) {
            return Compra::whereDate('data_compra', $hoje)
                ->sum('valor_total') ?? 0;
        });

        // Formatador reutilizável
        $formatarValor = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        // Gera dados para gráficos
        $dadosHoje = $this->gerarDadosHoje();
        $dadosMes = $this->gerarDadosMesAtual($anoAtual, $mesAtual);
        $dadosGeral = $this->gerarDadosGeral();

        return [
            Stat::make('Compras Hoje', $formatarValor($totalHoje))
                ->description($now->translatedFormat('d/m/Y'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success')
                ->chart($dadosHoje)
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                    'title' => 'Clique para ver detalhes das compras de hoje',
                ]),

            Stat::make('Compras do Mês', $formatarValor($totalMes))
                ->description($now->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning')
                ->chart($dadosMes)
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                    'title' => 'Clique para ver detalhes das compras deste mês',
                ]),

            Stat::make('Total de Compras', $formatarValor($totalGeral))
                ->description('Todo Período')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary')
                ->chart($dadosGeral)
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                    'title' => 'Clique para ver todas as compras',
                ]),
        ];
    }

    /**
     * Gera dados de hoje para gráfico (últimas 12 horas)
     */
    private function gerarDadosHoje(): array
    {
        $cacheKey = 'compras_dados_hoje_' . Carbon::now()->toDateString();
        
        return Cache::remember($cacheKey, now()->addMinutes(5), function () {
            $dados = [];
            $hoje = Carbon::now()->toDateString();
            
            // Últimas 12 horas
            for ($i = 11; $i >= 0; $i--) {
                $horaInicio = Carbon::now()->subHours($i + 1);
                $horaFim = Carbon::now()->subHours($i);
                
                $valor = Compra::whereDate('data_compra', $hoje)
                    ->whereTime('data_compra', '>=', $horaInicio->format('H:i:s'))
                    ->whereTime('data_compra', '<=', $horaFim->format('H:i:s'))
                    ->sum('valor_total') ?? 0;
                
                $dados[] = round($valor, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Gera dados do mês atual para gráfico (dias do mês)
     */
    private function gerarDadosMesAtual(int $ano, int $mes): array
    {
        $cacheKey = "compras_dados_mes_{$ano}_{$mes}";
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($ano, $mes) {
            $diasNoMes = Carbon::create($ano, $mes, 1)->daysInMonth;
            $diasTranscorridos = min($diasNoMes, Carbon::now()->day);
            $dados = [];
            
            for ($dia = 1; $dia <= $diasTranscorridos; $dia++) {
                $valor = Compra::whereYear('data_compra', $ano)
                    ->whereMonth('data_compra', $mes)
                    ->whereDay('data_compra', $dia)
                    ->sum('valor_total') ?? 0;
                
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
        $cacheKey = 'compras_dados_geral_12_meses';
        
        return Cache::remember($cacheKey, now()->addHours(1), function () {
            $dados = [];
            
            // Últimos 12 meses
            for ($i = 11; $i >= 0; $i--) {
                $data = Carbon::now()->subMonths($i);
                $ano = $data->year;
                $mes = $data->month;
                
                $valor = Compra::whereYear('data_compra', $ano)
                    ->whereMonth('data_compra', $mes)
                    ->sum('valor_total') ?? 0;
                
                $dados[] = round($valor, 2);
            }
            
            return $dados;
        });
    }

    /**
     * Obtém a média diária de compras no mês atual
     */
    private function getMediaDiariaMesAtual(): float
    {
        $now = Carbon::now();
        $anoAtual = $now->year;
        $mesAtual = $now->month;
        $cacheKey = "compras_media_diaria_{$anoAtual}_{$mesAtual}";
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($anoAtual, $mesAtual, $now) {
            $totalMes = Compra::whereYear('data_compra', $anoAtual)
                ->whereMonth('data_compra', $mesAtual)
                ->sum('valor_total') ?? 0;
            
            $diasTranscorridos = max(1, $now->day);
            return $totalMes / $diasTranscorridos;
        });
    }

    // Método para limpar cache quando necessário
    public static function clearCache(): void
    {
        $now = Carbon::now();
        $anoAtual = $now->year;
        $mesAtual = $now->month;
        $hoje = $now->toDateString();
        
        // Limpa caches principais
        Cache::forget("compras_total_geral");
        Cache::forget("compras_total_mes_{$anoAtual}_{$mesAtual}");
        Cache::forget("compras_total_hoje_{$hoje}");
        
        // Limpa caches de gráficos
        Cache::forget("compras_dados_hoje_{$hoje}");
        Cache::forget("compras_dados_mes_{$anoAtual}_{$mesAtual}");
        Cache::forget("compras_dados_geral_12_meses");
        Cache::forget("compras_media_diaria_{$anoAtual}_{$mesAtual}");
    }

    // Método para invalidar cache quando nova compra é registrada
    public static function invalidateCacheOnNewCompra(): void
    {
        $now = Carbon::now();
        $anoAtual = $now->year;
        $mesAtual = $now->month;
        $hoje = $now->toDateString();
        
        // Limpa caches do dia atual
        Cache::forget("compras_total_hoje_{$hoje}");
        Cache::forget("compras_dados_hoje_{$hoje}");
        
        // Limpa caches do mês atual
        Cache::forget("compras_total_mes_{$anoAtual}_{$mesAtual}");
        Cache::forget("compras_dados_mes_{$anoAtual}_{$mesAtual}");
        Cache::forget("compras_media_diaria_{$anoAtual}_{$mesAtual}");
        
        // Limpa caches gerais
        Cache::forget("compras_total_geral");
        Cache::forget("compras_dados_geral_12_meses");
    }
}