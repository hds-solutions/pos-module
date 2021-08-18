<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\BelongsToCompany;

abstract class X_POSEmployee extends Base\Pivot {
    use BelongsToCompany;

    protected $table = 'pos_employee';

}
