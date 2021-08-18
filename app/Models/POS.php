<?php

namespace HDSSolutions\Laravel\Models;

use Illuminate\Validation\Validator;

class POS extends X_POS {

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function cashBook() {
        return $this->belongsTo(CashBook::class);
    }

    public function employees() {
        return $this->belongsToMany(Employee::class, 'pos_employee', 'pos_id')
            ->using(POSEmployee::class)
            ->withTimestamps();
    }

}
