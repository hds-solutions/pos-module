<?php

namespace HDSSolutions\Finpar\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class POSMenu {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // // create a submenu
        $sub = backend()->menu()
            ->add(__('pos::pos.nav'), [
                'nickname'  => 'pos',
                'icon'      => 'cogs',
            ])->data('priority', 700);

        // get sales menu group
        $sales = backend()->menu()->get('sales');

        $this
            // append items to submenu
            ->pos($sub)
            ->payment($sub)
            ;

        // continue witn next middleware
        return $next($request);
    }

    private function pos(&$menu) {
        if (Route::has('backend.pos'))
            $menu->add(__('pos::pos.nav'), [
                'route'     => 'backend.pos',
                'icon'      => 'pos'
            ]);

        return $this;
    }

    private function payment(&$menu) {
        if (Route::has('backend.payment'))
            $menu->add(__('pos::payment.nav'), [
                'route'     => 'backend.payment',
                'icon'      => 'payment'
            ]);

        return $this;
    }

}
