<?php

namespace HDSSolutions\Finpar\DataTables;

use HDSSolutions\Finpar\Models\Empty as Resource;
use Yajra\DataTables\Html\Column;

class POSDataTable extends Base\DataTable {

    public function __construct() {
        parent::__construct(
            Resource::class,
            route('backend.empties'),
        );
    }

    protected function getColumns() {
        return [
            Column::computed('id')
                ->title( __('pos::empty.id.0') )
                ->hidden(),

            Column::make('name')
                ->title( __('pos::empty.name.0') ),

            Column::make('actions'),
        ];
    }

}
