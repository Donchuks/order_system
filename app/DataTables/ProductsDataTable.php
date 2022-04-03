<?php

namespace App\DataTables;

use App\Models\Product;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class ProductsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query): \Yajra\DataTables\DataTableAbstract
    {
        return datatables()->eloquent($query)
            ->editColumn('price', function ($row) {
                return $row->currency.' '.$row->price;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d M, Y H:i:s A');
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-danger" href="#"' .
                    'onclick="delete_notify('.$row->id.')" target="_self"><i class="fa fa-trash-o text-gray-dark" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-success" href="'.route('product.edit', [$row->id]).'"' .
                    '"><i class="fa fa-edit text-gray-dark" aria-hidden="true"></i></a>' .
//                    '<a class="btn btn-xs btn-danger" href="#"' .
//                    'onclick="delete_notify('.$row->id.')"><i class="fas fa-trash text-gray-dark" aria-hidden="true"></i></a>' .
                    '</div>';
            })
            ->rawColumns(['action', 'progress']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Product::query()->orderByDesc('created_at');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): \Yajra\DataTables\Html\Builder
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => 50])
            ->serverSide(true)
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
//                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name' => ['title' => 'Name', 'width' => 50],
            'price' => ['width' => 50],
            'created_at' => ['title' => 'Date&nbsp;Created', 'visible' => true, 'width' => 50],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'products_report_' . date('YmdHis');
    }
}
