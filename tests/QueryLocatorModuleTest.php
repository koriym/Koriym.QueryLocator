<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Ray\Di\Injector;

class QueryLocatorModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSql(): void
    {
        /* @var QueryLocator $queryLocator  */
        $queryLocator = (new Injector(new QueryLocatorModule(__DIR__ . '/sql')))->getInstance(QueryLocatorInterface::class);
        $this->assertInstanceOf(QueryLocatorInterface::class, $queryLocator);
        $sql = $queryLocator->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }
}
