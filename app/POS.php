<?php

namespace HDSSolutions\Laravel;

use HDSSolutions\Laravel\Models\POS as POSSetting;
use HDSSolutions\Laravel\Models\Branch;
use HDSSolutions\Laravel\Models\Currency;
use HDSSolutions\Laravel\Models\Customer;
use HDSSolutions\Laravel\Models\Employee;
use HDSSolutions\Laravel\Models\Warehouse;
use HDSSolutions\Laravel\Models\CashBook;
use HDSSolutions\Laravel\Models\Stamping;

class POS {

    private ?POSSetting $pos;
    private ?Employee $employee;

    public function configure(
        int|POSSetting $pos,
        int|Employee $employee,
    ) {
        // save POS settings to use
        $this->pos = $pos instanceof POSSetting ? $pos : POSSetting::findOrFail($pos);
        session([ 'pos.settings' => $this->pos->getKey() ]);

        // save employee
        $this->employee = $employee instanceof Employee ? $employee : Employee::findOrFail($employee);
        session([ 'pos.employee' => $this->employee->getKey() ]);
    }

    public function pos():?POSSetting {
        return $this->pos ??= POSSetting::firstWhere('id', session('pos.settings') );
    }

    public function employee():?Employee {
        return $this->employee ??= Employee::firstWhere('employees.id', session('pos.employee') );
    }

    public function currency():?Currency {
        return $this->pos()?->currency;
    }

    public function branch():?Branch {
        return $this->pos()?->branch;
    }

    public function warehouse():?Warehouse {
        return $this->pos()?->warehouse;
    }

    public function cashBook():?CashBook {
        return $this->pos()?->cashBook;
    }

    public function stamping():?Stamping {
        return $this->pos()?->stamping;
    }

    public function prepend():?string {
        return $this->pos()?->prepend;
    }

    public function customer():?Customer {
        return $this->pos()?->customer;
    }

}
