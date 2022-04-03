<?php

namespace App\DataTables;

use App\Models\OrderActivityLog;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class OrderActivityDataTable extends DataTable
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
            ->addColumn('user', function ($row) {
                if(!is_null($row->user))
                    return $row->user->fl_names();
                return 'N/A';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d M, Y H:i:s A');
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
        $query = OrderActivityLog::with(['user'])->where('order_id', $this->order_id)->orderByDesc('created_at');
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
//            ->addAction(['width' => 50])
            ->serverSide(true)
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
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
            'created_at' => ['title' => 'Date&nbsp;Logged', 'visible' => true, 'width' => 50],
            'current_state' => ['title' => 'Current&nbsp;State', 'width' => 50],
            'activity' => ['width' => 100],
            'user' => ['title' => 'Actor', 'width' => 100],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'order_activity_report_' . date('YmdHis');
    }
}
