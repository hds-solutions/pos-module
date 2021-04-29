<?php

use Illuminate\Support\Facades\Route;
use HDSSolutions\Finpar\Http\Controllers\{
    POSController,
};

Route::group([
    'prefix'        => config('backend.prefix'),
    'middleware'    => [ 'web', 'auth:'.config('backend.guard'), 'permission:pos' ],
], function() {
    // name prefix
    $name_prefix = [ 'as' => 'backend' ];

    Route::resource('pos', POSController::class, $name_prefix)
        ->only([ 'index', 'store' ])
        ->parameters([ 'pos' => 'resource' ])
        ->name('index', 'backend.pos');

    // Route::resource('empties',    EmptyController::class,   $name_prefix)
    //     ->parameters([ 'empties' => 'resource' ])
    //     ->name('index', 'backend.empties');

});
