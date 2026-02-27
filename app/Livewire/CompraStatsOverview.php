<?php

namespace App\Livewire;

use App\Models\Compra;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
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

        // Busca dados diretamente do banco
        $totalGeral = Compra::sum('valor_total') ?? 0;

        $totalMes = Compra::whereYear('data_compra', $anoAtual)
            ->whereMonth('data_compra', $mesAtual)
            ->sum('valor_total') ?? 0;

        $totalHoje = Compra::whereDate('data_compra', $hoje)
            ->sum('valor_total') ?? 0;

        // Formatador reutilizável
        $formatarValor = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        // Gera dados para gráficos
        $dadosHoje = $this->gerarDadosHoje();
        $dadosMes = $this->gerarDadosMesAtual($anoAtual, $mesAtual);
        $dadosGeral = $this->gerarDadosGeral();

        return [
            Stat::make('Compras Hoje', $formatarValor($totalHoje))
                ->description('Hoje')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success')
                ->chart($dadosHoje)
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                    'title' => 'Clique para ver detalhes das compras de hoje',
                ]),

            Stat::make('Compras do Mês', $formatarValor($totalMes))
                ->description('Este Mês')
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
    }

    /**
     * Gera dados do mês atual para gráfico (dias do mês)
     */
    private function gerarDadosMesAtual(int $ano, int $mes): array
    {
        $diasNoMes = Carbon::create($ano, $mes, 1)->daysInMonth;
        $diasTranscorridos = min($diasNoMes, Carbon::now()->day);
        $dados = [];
        
        for ($dia = 1; $dia <= $diasTranscorridos; $dia++) {
            $data = Carbon::create($ano, $mes, $dia);
            $valor = Compra::whereDate('data_compra', $data->toDateString())
                ->sum('valor_total') ?? 0;
            
            $dados[] = round($valor, 2);
        }
        
        return $dados;
    }

    /**
     * Gera dados gerais para gráfico (últimos 12 meses)
     */
    private function gerarDadosGeral(): array
    {
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
    }

    /**
     * Obtém a média diária de compras no mês atual
     */
    private function getMediaDiariaMesAtual(): float
    {
        $now = Carbon::now();
        $anoAtual = $now->year;
        $mesAtual = $now->month;
        
        $totalMes = Compra::whereYear('data_compra', $anoAtual)
            ->whereMonth('data_compra', $mesAtual)
            ->sum('valor_total') ?? 0;
        
        $diasTranscorridos = max(1, $now->day);
        return $totalMes / $diasTranscorridos;
    }
}