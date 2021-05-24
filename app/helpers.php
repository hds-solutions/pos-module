<?php

use HDSSolutions\Finpar\POS;

if (!function_exists('pos_settings')) {
    function pos_settings():POS {
        return app( POS::class );
    }
}
