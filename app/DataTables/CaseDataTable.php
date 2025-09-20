<?php

namespace App\DataTables;

use App\Models\ClientCase;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CaseDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('type_of_visa', function (ClientCase $case)
            {
                return __('message.'.$case->type_of_visa);
            })
            ->addColumn('action', '');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ClientCase $model): QueryBuilder
    {
        return $model->newQuery()->select('cases.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('cases-table')
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
            Column::make('contract_number')->title(__('message.contract_number'))->className('dt-body-center')->width('5%'),
            Column::make('type_of_visa')->title(__('message.type_of_visa'))->className('dt-body-center')->width('5%'),
            Column::make('status')->title(__('message.status'))->className('dt-body-center')->width('5%'),
            Column::computed('action')->title(__('message.action'))->render('\'<a href="/edit_user/\'+full.id+\'" class="ms-2"><i class="fa fa-pencil text-secondary"></i></a>\'')->className('dt-body-center')->width('1%')->exportable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Case_' . date('YmdHis');
    }
}
