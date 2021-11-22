<?php

use Illuminate\Support\Facades\Route;
use HDSSolutions\Laravel\Http\Controllers\{
    POSController,

    PointOfSaleController,
    PaymentController,
};

Route::group([
    'prefix'        => config('backend.prefix'),
    'middleware'    => [ 'web', 'auth:'.config('backend.guard') ],
], function() {
    // name prefix
    $name_prefix = [ 'as' => 'backend' ];

    Route::resource('pos',          POSController::class,   $name_prefix)
        ->parameters([ 'pos' => 'resource' ])
        ->name('index', 'backend.pos');

    Route::group([
        'prefix'        => 'pointofsale',
        'middleware'    => [ 'permission:pointofsale' ],
    ], function() {

        Route::get('/',             [ PointOfSaleController::class, 'index' ])
            ->name('backend.pointofsale');
        Route::post('/',            [ PointOfSaleController::class, 'session' ])
            ->name('backend.pointofsale.session');

        Route::get('create',        [ PointOfSaleController::class, 'create' ])
            ->name('backend.pointofsale.create');
        Route::post('create',       [ PointOfSaleController::class, 'store' ])
            ->name('backend.pointofsale.store');

        Route::get('{resource}',    [ PointOfSaleController::class, 'show' ])
            ->name('backend.pointofsale.show');
        Route::post('{resource}',   [ PointOfSaleController::class, 'pay' ])
            ->name('backend.pointofsale.pay');
        Route::delete('{resource}', [ PointOfSaleController::class, 'destroy' ])
            ->name('backend.pointofsale.destroy');
        Route::get('{resource}/print.pdf',  [ PointOfSaleController::class, 'print' ])
            ->name('backend.pointofsale.print');

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
