<?php

namespace App\DataTables\Users;

use App\Models\AuditTrail;
use Carbon\Carbon;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTableAbstract;

class AuditTrailDataTable extends DataTable
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
                return Carbon::parse($row->created_at)->format('d M, Y H:i:s');
            })

            ->addColumn('user', function($row) {
                return $row->user->fl_names();
            })

            ->editColumn('activity', function($row) {
                return $row->activity;
            })


            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="#"' .
                    'onclick="resolve('.$row->id.')"><i class="fas fa-check text-gray-dark" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-danger" href="#"' .
                    'onclick="delete_notify('.$row->id.')"><i class="fas fa-trash text-gray-dark" aria-hidden="true"></i></a>' .
                    '</div>';
            })->rawColumns(['permissions', 'status', 'action', 'respond']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = AuditTrail::with('user')->orderByDesc('created_at');
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
            ->parameters([
                'stateSave' => true,
                'dom' => 'Bfrtip',
                'buttons' => ['copy', 'excel', 'pdf', 'colvis', 'print'],
                'order' => [[0, 'asc']]
            ]);

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
            'user'          => ['width' => 50],
            'activity'   => ['width' => 100],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'user_audit_report' . now();
    }
}
