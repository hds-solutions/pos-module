<?php

namespace HDSSolutions\Laravel\Seeders;

class POSPermissionsSeeder extends Base\PermissionsSeeder {

    public function __construct() {
        parent::__construct('pos');
    }

    protected function permissions():array {
        return [
            $this->resource('pos'),
            'pointofsale'   => 'pos::pointofsale.permissions.*',
            'payment'       => 'pos::payment.permissions.*',
        ];
    }

    protected function afterRun():void {
        // append permissions to Cashier role
        $this->role('Seller', [
            // Allow creation of new people (through POS modal)
            'people.crud.create',
            // Allow customers listing (POS modal)
            'customers.crud.index',
            // Allow access to POS window
            'pointofsale',
        ]);
    }

}
