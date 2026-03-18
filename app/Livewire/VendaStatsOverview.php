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
    public ?Model $record = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Se não houver um registro (venda) selecionado, retorna vazio
        if (!$this->record) {
            return [];
        }

        $vendaId = $this->record->id;

        // Busca os dados diretamente do banco
        $stats = [
            'qtd_itens' => DB::table('itens_vendas')
                ->where('venda_id', $vendaId)
                ->sum('qtd') ?? 0,
            
            'valor_bruto' => DB::table('vendas')
                ->where('id', $vendaId)
                ->value('valor_total') ?? 0,

            'valor_liquido' => DB::table('vendas')
                ->where('id', $vendaId)
                ->value('valor_total_desconto') ?? 0,
        ];

        $formatarValor = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        // Calcula a variação percentual entre bruto e líquido (se aplicável)
        $descontoPercentual = 0;
        if ($stats['valor_bruto'] > 0) {
            $descontoPercentual = (($stats['valor_bruto'] - $stats['valor_liquido']) / $stats['valor_bruto']) * 100;
        }

        return [
            Stat::make('Quantidade de Itens', (int) $stats['qtd_itens'])
                ->description('Total de produtos na venda')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info')
                ->chart($this->gerarTendenciaItens($vendaId)),

            Stat::make('Valor Total Bruto', $formatarValor($stats['valor_bruto']))
                ->description('Sem descontos')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->chart($this->gerarTendenciaValores($vendaId, 'bruto')),

            Stat::make('Valor Líquido', $formatarValor($stats['valor_liquido']))
                ->description($descontoPercentual > 0 ? 'Desconto de ' . number_format($descontoPercentual, 1) . '%' : 'Sem desconto')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart($this->gerarTendenciaValores($vendaId, 'liquido'))
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    /**
     * Gera dados de tendência para quantidade de itens (últimos 6 itens adicionados)
     */
    private function gerarTendenciaItens(int $vendaId): array
    {
        $tendencia = [];
        
        // Busca as quantidades dos últimos 6 itens adicionados à venda
        $itensRecentes = DB::table('itens_vendas')
            ->where('venda_id', $vendaId)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->pluck('qtd')
            ->reverse() // Para mostrar do mais antigo para o mais recente
            ->values()
            ->toArray();
        
        // Se tiver menos de 6 itens, completa com zeros à esquerda
        $tendencia = array_pad($itensRecentes, -6, 0);
        
        return array_map('intval', $tendencia);
    }

    /**
     * Gera dados de tendência para valores (últimos 6 itens)
     */
    private function gerarTendenciaValores(int $vendaId, string $tipo): array
    {
        $tendencia = [];
        
        if ($tipo === 'bruto') {
            // Busca valores brutos dos últimos 6 itens
            $valoresRecentes = DB::table('itens_vendas')
                ->where('venda_id', $vendaId)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->pluck('valor_total')
                ->reverse()
                ->values()
                ->toArray();
        } else {
            // Busca valores líquidos dos últimos 6 itens
            $valoresRecentes = DB::table('itens_vendas')
                ->where('venda_id', $vendaId)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->pluck('valor_total_desconto')
                ->reverse()
                ->values()
                ->toArray();
        }
        
        // Se tiver menos de 6 itens, completa com zeros à esquerda
        $tendencia = array_pad($valoresRecentes, -6, 0);
        
        return array_map(function($valor) {
            return round($valor, 2);
        }, $tendencia);
    }

    /**
     * Calcula estatísticas adicionais da venda (opcional)
     */
    private function getEstatisticasAdicionais(int $vendaId): array
    {
        $itens = DB::table('itens_vendas')
            ->where('venda_id', $vendaId)
            ->selectRaw('
                COUNT(*) as total_itens,
                AVG(valor_total) as ticket_medio,
                MAX(valor_total) as item_mais_caro,
                MIN(valor_total) as item_mais_barato
            ')
            ->first();

        return [
            'total_itens' => $itens->total_itens ?? 0,
            'ticket_medio' => $itens->ticket_medio ?? 0,
            'item_mais_caro' => $itens->item_mais_caro ?? 0,
            'item_mais_barato' => $itens->item_mais_barato ?? 0,
        ];
    }
}