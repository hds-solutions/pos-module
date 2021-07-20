<?php

namespace HDSSolutions\Laravel\Seeders;

class POSPermissionsSeeder extends Base\PermissionsSeeder {

    public function __construct() {
        parent::__construct('pos');
    }

    protected function permissions():array {
        return [
            'pos'       => 'Point of Sale',
            'payment'   => 'Payments window',
        ];
    }

    protected function afterRun():void {
        // append permissions to Cashier role
        $this->role('Seller', [
            'pos',
            'payment',
        ]);
    }

}
