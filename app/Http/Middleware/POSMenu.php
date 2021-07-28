<?php

namespace HDSSolutions\Laravel\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class POSMenu extends Base\Menu {

    public function handle($request, Closure $next) {
        // // create a submenu
        $sub = backend()->menu()
            ->add(__('pos::pos.nav'), [
                'nickname'  => 'pos',
                'icon'      => 'donate',
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
        if (Route::has('backend.pos') && $this->can('pos'))
            $menu->add(__('pos::pos.nav'), [
                'route'     => 'backend.pos',
                'icon'      => 'pos'
            ]);

        return $this;
    }

    private function payment(&$menu) {
        if (Route::has('backend.payment') && $this->can('payment'))
            $menu->add(__('pos::payment.nav'), [
                'route'     => 'backend.payment',
                'icon'      => 'payment'
            ]);

        return $this;
    }

}
