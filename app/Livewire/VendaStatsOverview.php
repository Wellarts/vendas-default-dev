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

    public ?Model $record = null;

    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        // Se não houver um registro (venda) selecionado, retorna vazio ou estatísticas gerais
        if (!$this->record) {
            return [];
        }

        $vendaId = $this->record->id;
        $cacheKey = "venda_stats_overview_{$vendaId}";

        // Busca ou armazena os dados básicos no cache por 5 minutos
        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($vendaId) {
            return [
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
        });

        $formatarValor = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');

        return [
            Stat::make('Quantidade de Itens', (int) $stats['qtd_itens'])
                ->description('Total de produtos')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info')
                ->chart([3, 5, 2, 10, 5, $stats['qtd_itens']]), // Exemplo de tendência

            Stat::make('Valor Total Bruto', $formatarValor($stats['valor_bruto']))
                ->description('Sem descontos')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->chart([10, 20, 40, 30, 50, 80]),

            Stat::make('Valor Líquido', $formatarValor($stats['valor_liquido']))
                ->description('Com desconto aplicado')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([5, 15, 35, 25, 45, 75])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow',
                ]),
        ];
    }

    /**
     * Opcional: Método para limpar o cache desta venda específica
     * Chame TotalVendaStatsOverview::clearCache($venda->id) no Observer da Venda
     */
    public static function clearCache(int $vendaId): void
    {
        Cache::forget("venda_stats_overview_{$vendaId}");
    }
}