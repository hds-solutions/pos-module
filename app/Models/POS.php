<?php

namespace HDSSolutions\Laravel\Models;

use Illuminate\Validation\Validator;

class POS extends X_POS {

    public function currency() {
        return $this->belongsTo(Currency::class)
            ->withTrashed();
    }

    public function branch() {
        return $this->belongsTo(Branch::class)
            ->withTrashed();
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class)
            ->withTrashed();
    }

    public function cashBook() {
        return $this->belongsTo(CashBook::class)
            ->withTrashed();
    }

    public function stamping() {
        return $this->belongsTo(Stamping::class)
            ->withTrashed();
    }

    public function customer() {
        return $this->belongsTo(Customer::class)
            ->withTrashed();
    }

    public function employees() {
        return $this->belongsToMany(Employee::class, 'pos_employee', 'pos_id')
            ->using(POSEmployee::class)
            ->withTimestamps();
    }

}
