<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use function function_exists;

if (! function_exists('apcu_fetch')) {
    function apcu_fetch($key)
    {
        return isset($GLOBALS[__NAMESPACE__ . $key]) ? $GLOBALS[__NAMESPACE__ . $key] : false;
    }

    function apcu_store($key, $var)
    {
        $GLOBALS[__NAMESPACE__ . $key] = $var;

        return true;
    }
}
