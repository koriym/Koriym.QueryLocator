<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

interface QueryProviderInterface
{
    /**
     * @return string
     */
    public function get();
}
