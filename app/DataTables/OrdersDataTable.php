<?php

namespace App\DataTables;

use App\Models\Order;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            ->editColumn('box_id', function ($row) {
                if(!is_null($row->box_id))
                    return $row->box_id;
                return 'N/A';
            })
            ->addColumn('activity_log', function ($row) {
                if(!is_null($row->activity_logs))
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('order.activity', [$row->id]).'"' .
                        'onclick=""><i class="fa fa-eye text-gray-dark" aria-hidden="true"></i></a>' .
                        '</div>';
                return 'N/A';
            })
            ->addColumn('product', function ($row) {
                if(!is_null($row->product))
                    return $row->product->name;
                return 'N/A';
            })
            ->addColumn('price', function ($row) {
                if(!is_null($row->product))
                    return $row->product->currency.' '.$row->product->price;
                return 'N/A';
            })
            ->editColumn('created_at', function ($row) {
               return Carbon::parse($row->created_at)->format('d M, Y H:i:s A');
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="'.route('order.show', [$row->id]).'"' .
                    'onclick=""><i class="fa fa-eye text-gray-dark" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-danger" href="#"' .
                    'onclick="cancel_order('.$row->id.')" target="_self"><i class="fa fa-times text-gray-dark" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-success" href="'.route('order.edit', [$row->id]).'"' .
                    '"><i class="fa fa-edit text-gray-dark" aria-hidden="true"></i></a>' .
//                    '<a class="btn btn-xs btn-danger" href="#"' .
//                    'onclick="delete_notify('.$row->id.')"><i class="fas fa-trash text-gray-dark" aria-hidden="true"></i></a>' .
                    '</div>';
            })
            ->rawColumns(['action', 'activity_log']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Order::orderOpen()->orderPermission()->with(['product', 'activity_logs'])->orderByDesc('created_at');
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
            'activity_log' => ['title' => 'Activity&nbsp;Log', 'width' => 50],
            'name' => ['title' => 'Name', 'width' => 50],
            'phone' => ['width' => 50],
            'address' => ['width' => 100],
            'delivery_date' => ['title' => 'Delivery&nbsp;Date', 'width' => 50],
            'product' => ['width' => 50],
            'price' => ['width' => 50],
//            'payment_option' => ['title' => 'Payment', 'width' => 50],
            'order_status' => ['title' => 'Status', 'width' => 50],
//            'created_at' => ['title' => 'Date&nbsp;Created', 'visible' => true, 'width' => 50],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'orders_report_' . date('YmdHis');
    }
}
