<?php

use Illuminate\Support\Facades\Route;
use HDSSolutions\Finpar\Http\Controllers\{
    POSController,
    PaymentController,
};

Route::group([
    'prefix'        => config('backend.prefix'),
    'middleware'    => [ 'web', 'auth:'.config('backend.guard'), 'permission:pos' ],
], function() {
    // name prefix
    $name_prefix = [ 'as' => 'backend' ];

    Route::group([
        'prefix'        => 'pos',
        'middleware'    => [ 'permission:pos' ],
    ], function() {

        Route::get('/',             [ POSController::class, 'index' ])
            ->name('backend.pos');
        Route::post('/',            [ POSController::class, 'session' ])
            ->name('backend.pos.session');

        Route::get('create',        [ POSController::class, 'create' ])
            ->name('backend.pos.create');
        Route::post('create',       [ POSController::class, 'store' ])
            ->name('backend.pos.store');

        Route::get('{resource}',    [ POSController::class, 'show' ])
            ->name('backend.pos.show');
        Route::post('{resource}',   [ POSController::class, 'pay' ])
            ->name('backend.pos.pay');

    });

    Route::group([
        'prefix'        => 'payment',
        'middleware'    => [ 'permission:payment' ],
    ], function() {

        Route::get('/',             [ PaymentController::class, 'index' ])
            ->name('backend.payment');
        Route::post('/',            [ PaymentController::class, 'session' ])
            ->name('backend.payment.session');

        Route::get('create',        [ PaymentController::class, 'create' ])
            ->name('backend.payment.create');
        Route::post('create',       [ PaymentController::class, 'store' ])
            ->name('backend.payment.store');

        Route::get('{resource}',    [ PaymentController::class, 'show' ])
            ->name('backend.payment.show');
        Route::post('{resource}',   [ PaymentController::class, 'pay' ])
            ->name('backend.payment.pay');

    });

    // Route::resource('empties',    EmptyController::class,   $name_prefix)
    //     ->parameters([ 'empties' => 'resource' ])
    //     ->name('index', 'backend.empties');

});
