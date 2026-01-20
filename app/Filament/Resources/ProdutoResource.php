<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\ProdutoResource\Pages;
use App\Filament\Resources\ProdutoResource\RelationManagers;
use App\Filament\Resources\ProdutoResource\RelationManagers\ProdutoFornecedorRelationManager;
use App\Models\Produto;
use Closure;
use Dom\Notation;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?string $label = 'Produtos/Serviços';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cadastro')
                    ->columns([
                        'xl' => 3,
                        '2xl' => 3,
                    ])
                    ->schema([
                        Forms\Components\ToggleButtons::make('tipo')
                            ->label('Tipo')
                            ->default(1)
                            ->columnSpanFull()
                            ->options([
                                '1' => 'Produto',
                                '2' => 'Serviço',
                            ])
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state == 1) {
                                    $set('lucratividade', 0);
                                } elseif ($state == 2) {
                                    $set('lucratividade', 100);
                                    $set('valor_compra', null); // Limpa valor de compra para serviços
                                }
                            })
                            ->grouped(),
                        
                        Forms\Components\TextInput::make('nome')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('codbar')
                            ->label('Código de Barras')
                            ->hidden(fn (Get $get) => $get('tipo') == 2)
                            ->required(false),
                        
                        Forms\Components\TextInput::make('estoque')
                            ->numeric()
                            ->integer()
                            ->hidden(fn (Get $get) => $get('tipo') == 2)
                            ->default(0),
                        
                        Forms\Components\TextInput::make('valor_compra')
                            ->label('Valor Compra')
                            ->hidden(fn (Get $get) => $get('tipo') == 2)
                            ->numeric()
                            ->step(0.01)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                self::calcularValorVenda($get, $set);
                            }),
                        
                        Forms\Components\TextInput::make('lucratividade')
                            ->label('Lucratividade (%)')
                            ->default(0)
                            ->numeric()
                            ->step(0.01)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                self::calcularValorVenda($get, $set);
                            }),
                        
                        Forms\Components\TextInput::make('valor_venda')
                            ->label('Valor Venda')
                            ->numeric()
                            ->step(0.01)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                self::calcularLucratividade($get, $set);
                            }),
                        
                        FileUpload::make('foto')
                            ->label('Fotos')
                            ->directory('fotos-produtos')
                            ->visibility('public')
                            ->downloadable()
                            ->maxSize(1000)
                            ->maxFiles(1)
                            ->hidden(fn (Get $get) => $get('tipo') == 2),
                        
                        Forms\Components\Select::make('categoria_id')
                            ->label('Categoria')
                            ->relationship('categoria', 'nome')
                            ->searchable()
                            ->preload()
                            ->required(false)
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nome')
                                    ->label('Nome')
                                    ->required()
                            ])

                    ])->columns(2),
            ]);
    }

    /**
     * Calcula o valor de venda com base no valor de compra e lucratividade
     */
    private static function calcularValorVenda(Get $get, Set $set): void
    {
        $valorCompra = (float) ($get('valor_compra') ?? 0);
        $lucratividade = (float) ($get('lucratividade') ?? 0);
        
        if ($valorCompra > 0) {
            $valorVenda = $valorCompra + ($valorCompra * $lucratividade / 100);
            $set('valor_venda', number_format($valorVenda, 2, '.', ''));
        } else {
            // Se não há valor de compra, valor de venda é igual à lucratividade (para serviços)
            if ($get('tipo') == 2) {
                $set('valor_venda', $lucratividade);
            }
        }
    }

    /**
     * Calcula a lucratividade com base no valor de compra e valor de venda
     */
    private static function calcularLucratividade(Get $get, Set $set): void
    {
        // Para serviços, não calcula lucratividade baseada em valor de compra
        if ($get('tipo') == 2) {
            return;
        }
        
        $valorCompra = (float) ($get('valor_compra') ?? 0);
        $valorVenda = (float) ($get('valor_venda') ?? 0);
        
        if ($valorCompra > 0 && $valorVenda > 0) {
            $lucratividade = (($valorVenda - $valorCompra) / $valorCompra) * 100;
            $set('lucratividade', number_format($lucratividade, 2, '.', ''));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('tipo')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'warning',
                    })
                    ->formatStateUsing(function ($state) {
                        return $state == 1 ? 'Produto' : 'Serviço';
                    })
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('codbar')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('categoria.nome')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('estoque')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('valor_compra')
                    ->money('BRL')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('lucratividade')
                    ->label('Lucratividade (%)')
                    ->formatStateUsing(fn($state) => number_format($state, 2) . '%')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('valor_venda')
                    ->money('BRL')
                    ->sortable(),
                
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->alignCenter()
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Categoria')
                    ->relationship('categoria', 'nome')
                    ->multiple()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        '1' => 'Produto',
                        '2' => 'Serviço',
                    ])
                    ->label('Tipo'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('catalogo')
                    ->label('Catálogo')
                    ->url(route('catalogo'))
                    ->icon('heroicon-s-shopping-bag')
                    ->openUrlInNewTab()
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (\Filament\Tables\Actions\DeleteAction $action, Produto $record) {
                        if ($record->itensVenda()->exists() || $record->pdv()->exists()) {
                            Notification::make()
                                ->title('Ação cancelada')
                                ->body('Este produto não pode ser excluído porque está vinculado a uma ou mais vendas.')
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
            ])
            ->defaultSort('nome', 'asc')
            ->deferLoading(); // Melhora performance em listagens grandes
    }

    public static function getRelations(): array
    {
        return [
            ProdutoFornecedorRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}