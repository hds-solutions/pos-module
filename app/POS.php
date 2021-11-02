<?php

namespace HDSSolutions\Laravel;

use HDSSolutions\Laravel\Models\Branch;
use HDSSolutions\Laravel\Models\Currency;
use HDSSolutions\Laravel\Models\Customer;
use HDSSolutions\Laravel\Models\Warehouse;
use HDSSolutions\Laravel\Models\CashBook;
use HDSSolutions\Laravel\Models\Stamping;

class POS {

    private ?Currency $currency;
    private ?Branch $branch;
    private ?Warehouse $warehouse;
    private ?CashBook $cashBook;
    private ?Stamping $stamping;
    private ?string $prepend;
    private ?Customer $customer;

    public function configure(
        int|Currency $currency,
        int|Branch $branch,
        int|Warehouse $warehouse,
        int|CashBook $cashBook,
        int|Stamping $stamping,
        string $prepend,
        int|Customer $customer
    ) {
        // save currency
        $this->currency = backend()->currencies()->firstWhere('id', $currency instanceof Currency ? $currency->id : $currency);
        session([ 'pos.currency' => $this->currency->getKey() ]);
        // save branch
        $this->branch = $branch instanceof Branch ? $branch : Branch::findOrFail($branch);
        session([ 'pos.branch' => $this->branch->getKey() ]);
        // save warehouse
        $this->warehouse = $warehouse instanceof Warehouse ? $warehouse : Warehouse::findOrFail($warehouse);
        session([ 'pos.warehouse' => $this->warehouse->getKey() ]);
        // save cashBook
        $this->cashBook = $cashBook instanceof CashBook ? $cashBook : CashBook::findOrFail($cashBook);
        session([ 'pos.cashBook' => $this->cashBook->getKey() ]);
        // save stamping
        $this->stamping = $stamping instanceof Stamping ? $stamping : Stamping::findOrFail($stamping);
        session([ 'pos.stamping' => $this->stamping->getKey() ]);
        // save prepend
        $this->prepend = $prepend;
        session([ 'pos.prepend' => $this->prepend ]);
        // save customer
        $this->customer = $customer instanceof Customer ? $customer : Customer::findOrFail($customer);
        session([ 'pos.customer' => $this->customer->getKey() ]);
    }

    public function currency():?Currency {
        // return configured model
        return $this->currency ??= $this->loadCurrency();
    }

    public function branch():?Branch {
        // return configured model
        return $this->branch ??= $this->loadBranch();
    }

    public function warehouse():?Warehouse {
        // return configured model
        return $this->warehouse ??= $this->loadWarehouse();
    }

    public function cashBook():?CashBook {
        // return configured model
        return $this->cashBook ??= $this->loadCashBook();
    }

    public function stamping():?Stamping {
        // return configured model
        return $this->stamping ??= $this->loadStamping();
    }

    public function prepend():?string {
        // return configured model
        return $this->prepend ??= session('pos.prepend');
    }

    public function customer():?Customer {
        // return configured model
        return $this->customer ??= $this->loadCustomer();
    }

    private function loadCurrency():?Currency  {
        // load configured model from session
        return $this->currency = backend()->currencies()->firstWhere('id', session('pos.currency') );
    }

    private function loadBranch():?Branch  {
        // load configured model from session
        return $this->branch = backend()->company()->branches->firstWhere('id', session('pos.branch') );
    }

    private function loadWarehouse():?Warehouse {
        // load configured model from session
        return $this->warehouse = $this->branch()->warehouses->firstWhere('id', session('pos.warehouse') );
    }

    private function loadCashBook():?CashBook {
        // load configured model from session
        return $this->cashBook = CashBook::firstWhere('id', session('pos.cashBook') );
    }

    private function loadStamping():?Stamping {
        // load configured model from session
        return $this->stamping = Stamping::firstWhere('id', session('pos.stamping') );
    }

    private function loadCustomer():?Customer {
        // load configured model from session
        return $this->customer = Customer::firstWhere('customers.id', session('pos.customer') );
    }

}
