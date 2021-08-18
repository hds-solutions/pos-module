<?php

namespace HDSSolutions\Laravel\DataTables;

use HDSSolutions\Laravel\Models\POS as Resource;
use Yajra\DataTables\Html\Column;

class POSDataTable extends Base\DataTable {

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

            Column::computed('actions'),
        ];
    }

}
