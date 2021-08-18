<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\BelongsToCompany;

abstract class X_POS extends Base\Model {
    use BelongsToCompany;

    protected $table = 'pos';

    protected $orderBy = [
        'name',
    ];

    protected $fillable = [
        'name',
        'currency_id',
        'branch_id',
        'warehouse_id',
        'cash_book_id',
    ];

}
