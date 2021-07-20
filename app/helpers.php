<?php

use HDSSolutions\Laravel\POS;

if (!function_exists('pos_settings')) {
    function pos_settings():POS {
        return app( POS::class );
    }
}
