<?php

namespace HDSSolutions\Laravel;

use HDSSolutions\Laravel\Models\Branch;
use HDSSolutions\Laravel\Models\Currency;
use HDSSolutions\Laravel\Models\Warehouse;
use HDSSolutions\Laravel\Models\CashBook;

class POS {

    private ?Currency $currency;
    private ?Branch $branch;
    private ?Warehouse $warehouse;
    private ?CashBook $cashBook;

    public function configure(int|Currency $currency, int|Branch $branch, int|Warehouse $warehouse, int|CashBook $cashBook) {
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

}
