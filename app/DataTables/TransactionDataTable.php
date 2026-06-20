<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function (Transaction $transaction) {
                $show = route('transaction.show', $transaction->id);
                if (auth()->user()->roles === 'admin') {
                    $edit   = route('transaction.edit', $transaction->id);
                    $delete = route('transaction.destroy', $transaction->id);
                    return '
                        <a href="' . $show . '" class="btn btn-sm btn-info text-white me-1">show</a>
                        <a href="' . $edit . '" class="btn btn-sm btn-warning me-1">edit</a>
                        <form action="' . $delete . '" method="POST" style="display:inline;"
                            onsubmit="return confirm(\'Yakin ingin menghapus transaksi ini?\')">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger">delete</button>
                        </form>';
                } else {
                    return '<a href="' . $show . '" class="btn btn-sm btn-info text-white">show</a>';
                }
            })
            ->addColumn('harga_total', function (Transaction $transaction) {
                return 'Rp ' . number_format($transaction->total_price, 0, ',', '.');
            })
            ->addColumn('status_badge', function (Transaction $transaction) {
                $status = strtoupper($transaction->status);
                $badgeClass = 'bg-secondary';
                
                switch ($status) {
                    case 'PENDING':
                        $badgeClass = 'bg-warning text-dark';
                        break;
                    case 'SUCCESS':
                    case 'DELIVERED':
                        $badgeClass = 'bg-success';
                        break;
                    case 'FAILED':
                        $badgeClass = 'bg-danger';
                        break;
                    case 'SHIPPING':
                        $badgeClass = 'bg-primary';
                        break;
                    case 'CHALLENGE':
                        $badgeClass = 'bg-info text-dark';
                        break;
                    case 'CANCELLED':
                        $badgeClass = 'bg-secondary';
                        break;
                }
                
                return '<span class="badge ' . $badgeClass . '">' . $status . '</span>';
            })
            ->rawColumns(['action', 'status_badge'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Transaction $model): QueryBuilder
    {
        $query = $model->newQuery()->select([
            'id',
            'users_id',
            'name',
            'email',
            'phone',
            'courier',
            'total_price',
            'status',
            'created_at'
        ]);

        if (auth()->user()->roles !== 'admin') {
            $query->where('users_id', auth()->id());
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('transaction-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(7, 'desc') // Order by created_at desc
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                  ->title('NO')
                  ->exportable(false)
                  ->printable(false)
                  ->width(50)
                  ->addClass('text-center'),
            Column::make('name')->title('NAMA PENERIMA'),
            Column::make('email')->title('EMAIL'),
            Column::make('phone')->title('TELEPON'),
            Column::make('courier')->title('KURIR'),
            Column::computed('harga_total')->title('TOTAL HARGA')->orderable(false)->searchable(false),
            Column::computed('status_badge')->title('STATUS')->exportable(false)->printable(false),
            Column::make('created_at')->title('TANGGAL')->visible(false),
            Column::computed('action')
                  ->title('Action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(130)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
