<?php

namespace App\DataTables\Users;

use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTableAbstract;

class PermissionsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query): DataTableAbstract
    {
        return datatables()->eloquent($query)

            ->addColumn('date', function($row) {
                return Carbon::parse($row->created_at)->format('d M, Y');
            })

            ->editColumn('name', function($row) {
                return $row->name;
            })

            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="#"' .
                    'onclick="resolve('.$row->id.')"><i class="fa fa-check text-gray-dark" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-danger" href="#"' .
                    'onclick="delete_notify('.$row->id.')"><i class="fa fa-trash text-gray-dark" aria-hidden="true"></i></a>' .
                    '</div>';
            })->rawColumns(['assigned_modules', 'status', 'action', 'respond']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Permission::query()->orderByDesc('created_at');
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
    protected function getColumns(): array
    {
        return [
            'date'          => ['width' => 50],
            'name'   => ['width' => 100],
            'guard_name'   => ['width' => 50],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'permissions_report' . now();
    }
}
