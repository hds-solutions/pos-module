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
        'stamping_id',
        'customer_id',
        'price_list_id',
        'prepend',
        'length',
        'start',
        'end',
        'current',
    ];

    protected static array $rules = [
        'length'            => [ 'required_if:is_purchase,false', 'nullable', 'numeric', 'min:1' ],
        'start'             => [ 'required_if:is_purchase,false', 'nullable', 'numeric', 'lt:end' ],
        'end'               => [ 'required_if:is_purchase,false', 'nullable', 'numeric', 'gt:start' ],
        'current'           => [ 'sometimes', 'nullable', 'numeric', 'gte:start', 'lte:end' ],
    ];

}
