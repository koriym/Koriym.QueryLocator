<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

interface QueryProviderInterface
{
    public function get() : string;
}
