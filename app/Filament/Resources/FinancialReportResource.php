<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialReportResource\Pages\ListFinancialReports;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\DB;

class FinancialReportResource extends Resource
{
    protected static ?string $navigationLabel = 'Reporte Financiero';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 35;

    // No usamos $model

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Fecha')->date(),
                TextColumn::make('invoice_number')->label('Nº Factura')->default('-'),
                TextColumn::make('amount')->label('Importe')->money('EUR', true),
                TextColumn::make('type')->label('Tipo'),
            ])
            ->filters([
                Filter::make('from')
                    ->label('Desde')
                    ->form([
                        // Tables\Forms\Components\DatePicker::make('from'),
                    ])
                    ->query(fn($query, $data) => $data['from'] ? $query->where('date', '>=', $data['from']) : $query),

                Filter::make('until')
                    ->label('Hasta')
                    ->form([
                        // Tables\Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn($query, $data) => $data['until'] ? $query->where('date', '<=', $data['until']) : $query),
            ])
            ->getTableQuery(function () {
                $sales = DB::table('orders')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->select(
                        'orders.date',
                        'orders.code as invoice_number',
                        DB::raw('(order_details.price * order_details.quantity + order_details.taxes) as amount'),
                        DB::raw("'Ingreso' as type")
                    )
                    ->where('orders.status', 'invoiced');

                $expenses = DB::table('other_expenses')
                    ->join('other_expense_details', 'other_expenses.id', '=', 'other_expense_details.other_expense_id')
                    ->select(
                        'other_expenses.date',
                        DB::raw("NULL as invoice_number"),
                        'other_expense_details.price as amount',
                        DB::raw("'Gasto' as type")
                    );

                return $sales->unionAll($expenses);
            })
            ->defaultSort('date', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialReports::route('/'),
        ];
    }
}
