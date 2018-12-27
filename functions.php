<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

function apc_fetch($key)
{
    return isset($GLOBALS[__NAMESPACE__ . $key]) ? $GLOBALS[__NAMESPACE__ . $key] : false;
}

function apc_store($key, $var)
{
    $GLOBALS[__NAMESPACE__ . $key] = $var;

    return true;
}
