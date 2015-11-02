<?php

namespace Koriym\QueryLocator;

class ApcQueryLocatorTest extends QueryLocatorTest
{
    /**
     * @var QueryLocator
     */
    protected $query;

    protected function setUp()
    {
        parent::setUp();
        $sqlDir = __DIR__ . '/sql';
        $this->query = new ApcQueryLocator($sqlDir, 'foo-namespace');
    }
}
