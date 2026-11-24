<?php

declare(strict_types=1);

namespace MyVendor\MyPacakge;

use Koriym\QueryLocator\QueryLocatorInject;

class FakeQueryConsumer
{
    use QueryLocatorInject;

    public function getSql(string $queryName): string
    {
        return $this->query->get($queryName);
    }
}
