<?php

declare(strict_types=1);

if (! function_exists('apcu_fetch')) {
    /**
     * @return string|false
     */
    function apcu_fetch(string $key)
    {
        return isset($GLOBALS[__NAMESPACE__ . $key]) ? $GLOBALS[__NAMESPACE__ . $key] : false;
    }

    /**
     * @param string $key
     * @param mixed  $var
     *
     * @return true
     */
    function apcu_store($key, $var)
    {
        $GLOBALS[__NAMESPACE__ . $key] = $var;

        return true;
    }
}
