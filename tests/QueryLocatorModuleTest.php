<?php

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;
use Ray\Di\Injector;

class QueryLocatorModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSql()
    {
        /** @var $queryLocator QueryLocator */
        $queryLocator = (new Injector(new QueryLocatorModule(__DIR__ . '/sql')))->getInstance(QueryLocatorInterface::class);
        $this->assertInstanceOf(QueryLocatorInterface::class, $queryLocator);
        $sql = $queryLocator->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }
}
