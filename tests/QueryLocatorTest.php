<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;

class QueryLocatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var QueryLocator
     */
    protected $query;

    protected function setUp(): void
    {
        parent::setUp();
        $sqlDir = __DIR__ . '/sql';
        $this->query = new QueryLocator($sqlDir);
    }

    public function testGetSql(): void
    {
        $sql = $this->query->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetSqlCached(): void
    {
        $sql = $this->query->get('admin/user');
        $sql = $this->query->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testArrayAccess(): void
    {
        $sql = $this->query['admin/user'];
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testNotFound(): void
    {
        $this->expectException(QueryFileNotFoundException::class);
        $this->query['user/not_exist_sql'];
    }

    public function testGetCountSql(): void
    {
        $sql = $this->query->getCountQuery('admin/user');
        $expected = 'SELECT COUNT(*) FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlCached(): void
    {
        $sql = $this->query->getCountQuery('admin/user');
        $sql = $this->query->getCountQuery('admin/user');
        $expected = 'SELECT COUNT(*) FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlFailed(): void
    {
        $this->expectException(CountQueryException::class);
        $this->query->getCountQuery('admin/distinct');
    }

    public function testOffsetExists(): void
    {
        $isSet = isset($this->query['admin/user']);
        $this->assertTrue($isSet);
    }

    public function testOffsetUnset(): void
    {
        $this->expectException(ReadOnlyException::class);
        unset($this->query['admin/user']);
    }

    public function testSet(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->query['admin/user'] = 'A SQL';
    }
}
