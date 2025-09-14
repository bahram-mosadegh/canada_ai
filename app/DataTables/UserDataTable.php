<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('role', function (User $user)
            {
                return __('message.'.$user->role);
            })
            ->addColumn('action', '');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->select('users.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->initComplete("function () {
                        this.api().columns().every(function () {
                            var column = this;
                            var input = document.createElement(\"input\");
                            input.className = 'form-control';
                            input.placeholder = '".__('message.search')."';
                            $(input).appendTo($(column.footer()).empty())
                            .on('keyup', delay(function () {
                                    column.search($(this).val(), false, false, true).draw();
                                }, 500));
                        });

                        this.api().columns(['role:name']).every(function () {
                            var column = this;
                            var select = $('<select class=\"form-control\"> <option value=\"\">-- ".__('message.select')." --</option><option value=\"admin\">".__('message.admin')."</option><option value=\"user\">".__('message.user')."</option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                        });

                        this.api().columns(['active:name']).every(function () {
                            var column = this;
                            var select = $('<select class=\"form-control\"> <option value=\"\">-- ".__('message.select')." --</option><option value=\"1\">".__('message.active')."</option><option value=\"0\">".__('message.inactive')."</option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });
                        });

                        this.api().columns(['action:name']).every(function () {
                            var column = this;
                            $('<button type=\"button\" class=\"btn btn-outline-secondary w-100\" style=\"margin-bottom: 0px;\">".__('message.clear_filters')."</button>')
                                .appendTo($(column.footer()).empty())
                                .on('click', function () {
                                    location.reload();
                                });
                        });
                    }")
                    ->dom('lBfrtip')
                    ->buttons(
                        Button::make('excel')->attr(['class' => 'btn btn-outline-secondary btn-md']),
                    )
                    ->parameters([
                        'pageLength' => 5,
                        'lengthMenu' => [
                            [5, 10, 25, 50, 100, 500],
                            [5, 10, 25, 50, 100, 500]
                        ],
                        'searchDelay' => '500',
                        'paging' => true,
                        'searching' => true,
                        'info' => true,
                        'responsive' => true,
                        'language' => [
                            'url' => url('../assets/data-tables/fa.json')
                        ],
                    ])
                    ->orderBy(0, 'desc');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('message.id'))->className('dt-body-center')->width('1%'),
            Column::make('name')->title(__('message.name'))->className('dt-body-center')->width('5%'),
            Column::make('last_name')->title(__('message.last_name'))->className('dt-body-center')->width('5%'),
            Column::make('email')->title(__('message.email'))->className('dt-body-center')->width('5%'),
            Column::make('mobile')->title(__('message.mobile'))->className('dt-body-center')->width('5%'),
            Column::make('role')->title(__('message.role'))->render('\'<button type="button" class="btn bg-gradient-\'+(data == \''.__('message.admin').'\' ? \'info\' : \'secondary\')+\' btn-sm m-0">\'+data+\'</button>\'')->className('dt-body-center')->width('5%'),
            Column::computed('active')->title(__('message.active'))->render('\'<div class="form-switch ps-0"><input onclick="change_user_status(\'+full.id+\', \'+(data==1?0:1)+\');" class="form-check-input ms-auto" type="checkbox" \'+(data==1?\'checked\':\'\')+\' \'+('.auth()->user()->id.'==full.id ? \'disabled\' : \'\')+\'></div>\'')->className('dt-body-center')->width('1%'),
            Column::computed('action')->title(__('message.action'))->render('\'<a href="/edit_user/\'+full.id+\'" class="ms-2"><i class="fa fa-pencil text-secondary"></i></a>\'')->className('dt-body-center')->width('1%')->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
