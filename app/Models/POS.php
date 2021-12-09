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

    public function priceList() {
        return $this->belongsTo(PriceList::class)
            ->withTrashed();
    }

    public function employees() {
        return $this->belongsToMany(Employee::class, 'pos_employee', 'pos_id')
            ->using(POSEmployee::class)
            ->withTimestamps();
    }

    public function getStartAttribute():?string {
        return $this->attributes['start'] !== null
            ? str_pad( $this->attributes['start'], $this->length, 0, STR_PAD_LEFT )
            : null;
    }

    public function getEndAttribute():?string {
        return $this->attributes['end'] !== null
            ? str_pad( $this->attributes['end'], $this->length, 0, STR_PAD_LEFT )
            : null;
    }

    public function getCurrentAttribute():?string {
        return $this->attributes['current'] !== null
            ? str_pad( $this->attributes['current'], $this->length, 0, STR_PAD_LEFT )
            : null;
    }

    public function getNextDocumentNumber():?string {
        // get next document number and save it
        $this->update([ 'current' => $next = $this->current ? str_increment($this->current) : $this->start ]);
        // return document number
        return $this->prepend.$next;
    }

}
