<?php

namespace App\DataTables;

use App\Events;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EventsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('checkbox', function($row) {     
                return '<input name="checkbox[]" class="checkbox" type="checkbox" id="chk1_'.$row->id.'" value="'.$row->id.'" />';
            })
            ->addColumn('banner', function($row) {     
                return '<img src="'.$row->banner_image.'" alt="Banner Imnage" width="100px" />';
            })
            ->addColumn('start_date', function($row) {     
                return $row->start_date_formatted;
            })
            ->addColumn('end_date', function($row) {     
                return $row->end_date_formatted;
            })
            ->addColumn('action', function($row) {     
                $btn = "<a href='javascript:void(0)' class='m-1 event edit btn btn-primary btn-sm' data-currentRow='".json_encode($row)."' >Edit</a>";     
                $btn .= "&nbsp;<a href='javascript:void(0)' class='m-1 event delete btn btn-danger btn-sm' data-currentRowId='".$row->id."' >Delete</a>";
                return $btn;
            })
            ->rawColumns(['checkbox', 'action', 'banner']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\App\Event $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Events $model)
    {
        return $model->orderBy('id', 'desc')->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('events-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        // Button::make('reload')
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
            Column::make('checkbox')->title('#')->exportable(false)->printable(false),
            Column::make('id'),
            Column::make('banner')->exportable(false)->printable(false),
            Column::make('name'),
            Column::make('location'),
            Column::make('start_date'),
            Column::make('end_date'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  // ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Events_' . date('YmdHis') . rand();
    }
}
