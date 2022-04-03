<?php

namespace App\DataTables\Users;

use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTableAbstract;

class UserPermissionsDataTable extends DataTable
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

            ->addColumn('name', function($row) {
                return $row->users->first()->fl_names();
            })

            ->addColumn('permissions', function($row) {
                $b = null;
                $row->permissions->each(function ($permission) use(&$b){
                    $b .= "<b>{$permission->name}</b><br>";
                });
                return sizeof($row->permissions) > 0 ? rtrim($b,' ,') : 'N/A';
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
        $query = Role::findById($this->role_id)->with(['permissions', 'users'])->whereHas('users', function ($q){
            $q->where('id', $this->user_id);
        });
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
    protected function getColumns(): array
    {
        return [
            'name'          => ['width' => 50],
            'permissions'   => ['width' => 100],
//            'guard_name'   => ['width' => 50],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'user_permissions_report' . now();
    }
}
