<?php

namespace App\DataTables\Users;

use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTableAbstract;

class UserDataTable extends DataTable
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

            ->editColumn('created_at', function($row) {
                return Carbon::parse($row->created_at)->format('d M, Y');
            })

            ->addColumn('name', function($row) {
                return $row->fl_names();
            })

            ->editColumn('gender', function($row) {
                return strtoupper($row->gender);
            })

            ->addColumn('role', function($row) {
                return $row->getRole();
            })

            ->addColumn('assigned_modules', function($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success" href="'.route('users.user.permissions', [$row->id, $row->roles->first()->id]).'"' .
                    'onclick=""><i class="fa fa-eye text-gray-dark" aria-hidden="true"></i></a>' .
                    '</div>';
            })

            ->editColumn('status', function($row) {
                if($row->status == 'active')
                    return '<span class="badge bg-success text-white">ACTIVE</span>';

                return '<span class="badge bg-warning text-white">INACTIVE</span>';
            })

            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="#"' .
                    'onclick="grab_edits('.$row->id.')"><i class="fa fa-edit text-gray-dark" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-danger" href="#"' .
                    'onclick="delete_notify('.$row->id.')"><i class="fa fa-trash-o text-gray-dark" aria-hidden="true"></i></a>' .
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
        $query = User::isActive()->orderByDesc('created_at');
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
            'created_at'          => ['width' => 50],
            'name'   => ['width' => 100],
            'gender'   => ['width' => 50],
            'role'   => ['width' => 50],
            'assigned_modules'   => ['title' => 'Assigned&nbsp;Modules', 'width' => 50],
            'status'   => ['width' => 50],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'users_report' . now();
    }
}
