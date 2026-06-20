<?php

namespace App\DataTables;

use App\Models\ProductGallery;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductGalleryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function (ProductGallery $gallery) {
                $edit   = route('product-gallery.edit', $gallery->id);
                $delete = route('product-gallery.destroy', $gallery->id);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-warning">edit</a>
                    <form action="' . $delete . '" method="POST" style="display:inline;"
                        onsubmit="return confirm(\'Yakin ingin menghapus foto produk ini?\')">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-danger">delete</button>
                    </form>';
            })
            ->addColumn('preview', function (ProductGallery $gallery) {
                $url = asset('storage/' . $gallery->url);
                return '<img src="' . $url . '" alt="Preview" style="max-height: 80px; max-width: 120px;" class="img-thumbnail" />';
            })
            ->rawColumns(['action', 'preview'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductGallery $model): QueryBuilder
    {
        // Eager load product relation to show product name
        return $model->newQuery()->with('product')->select('product_galleries.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-gallery-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
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
            Column::make('product.name')->title('NAMA PRODUK'),
            Column::computed('preview')->title('FOTO/PREVIEW')->orderable(false)->searchable(false),
            Column::make('description')->title('DESKRIPSI'),
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
        return 'ProductGallery_' . date('YmdHis');
    }
}
