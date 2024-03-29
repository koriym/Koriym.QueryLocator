<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

class ApcQueryLocatorTest extends QueryLocatorTest
{
    /**
     * @var ApcQueryLocator
     */
    protected $query;

    protected function setUp(): void
    {
        parent::setUp();
        $sqlDir = __DIR__ . '/sql';
        $this->query = new ApcQueryLocator($sqlDir, 'foo-namespace');
    }
}
