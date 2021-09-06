<?php

namespace HDSSolutions\Laravel\DataTables;

use HDSSolutions\Laravel\Models\POS as Resource;
use Yajra\DataTables\Html\Column;

class POSDataTable extends Base\DataTable {

    protected array $with = [
        'stamping',
    ];

    public function __construct() {
        parent::__construct(
            Resource::class,
            route('backend.pos'),
        );
    }

    protected function getColumns() {
        return [
            Column::computed('id')
                ->title( __('pos::pos.id.0') )
                ->hidden(),

            Column::make('name')
                ->title( __('pos::pos.name.0') ),

            Column::make('prepend')
                ->title( __('pos::pos.prepend.0') ),

            Column::make('stamping.document_number')
                ->title( __('pos::pos.stamping_id.0') )
                ->renderRaw('view:pos')
                ->data( view('pos::pos.datatable.stamping')->render() ),

            Column::computed('actions'),
        ];
    }

}
