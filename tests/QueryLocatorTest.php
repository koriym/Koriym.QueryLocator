<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\ExceptionInterface;
use Koriym\QueryLocator\Exception\LogicException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;
use Koriym\QueryLocator\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class QueryLocatorTest extends TestCase
{
    protected QueryLocatorInterface $query;

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
        $this->assertIsString($this->query['user/not_exist_sql']);
    }

    public function testGetCountSql(): void
    {
        $sql = $this->query->getCountQuery('admin/user_count');
        $expected = 'SELECT COUNT(*) FROM usr';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlCached(): void
    {
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

    public function testNotExists(): void
    {
        $this->expectException(QueryFileNotFoundException::class);
        $this->assertIsString($this->query['admin/_not_existing_']);
    }

    public function testGetSqlIsTrimmed(): void
    {
        $sql = $this->query->get('admin/user_order_limit');
        $expected = "SELECT id, name\nFROM usr\nWHERE id > 1\nORDER BY id DESC\nLIMIT 10";
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlRemovesOrderByAndLimit(): void
    {
        $sql = $this->query->getCountQuery('admin/user_order_limit');
        $expected = "SELECT COUNT(*) FROM usr\nWHERE id > 1";
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlCaseInsensitive(): void
    {
        $sql = $this->query->getCountQuery('admin/user_lowercase');
        $expected = 'SELECT COUNT(*) FROM usr';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlGroupByFailed(): void
    {
        $this->expectException(CountQueryException::class);
        $this->expectExceptionMessage('SELECT dept, COUNT(*) FROM usr GROUP BY dept;');
        $this->query->getCountQuery('admin/group_by');
    }

    public function testGetCountSqlSubQueryInSelectFailed(): void
    {
        $this->expectException(CountQueryException::class);
        $this->query->getCountQuery('admin/subquery_in_select');
    }

    public function testGetCountSqlSubQueryWithLimitFailed(): void
    {
        $this->expectException(CountQueryException::class);
        $this->query->getCountQuery('admin/subquery_with_limit');
    }

    public function testGetCountSqlFailedMessageIsSql(): void
    {
        $this->expectExceptionMessage('SELECT DISTINCT id FROM usr;');
        $this->query->getCountQuery('admin/distinct');
    }

    public function testNotFoundMessageIsFilePath(): void
    {
        $this->expectException(QueryFileNotFoundException::class);
        $this->expectExceptionMessage(__DIR__ . '/sql/admin/_not_existing_.sql');
        $this->query->get('admin/_not_existing_');
    }

    public function testOffsetExistsEmptySql(): void
    {
        $this->assertFalse(isset($this->query['admin/empty']));
    }

    public function testOffsetExistsNotFound(): void
    {
        $this->expectException(QueryFileNotFoundException::class);
        $this->assertTrue(isset($this->query['admin/_not_existing_']));
    }

    public function testReadOnlyMessage(): void
    {
        $this->expectException(ReadOnlyException::class);
        $this->expectExceptionMessage('not supported');
        $this->query['admin/user'] = 'A SQL';
    }

    public function testExceptionHierarchy(): void
    {
        foreach ([CountQueryException::class, LogicException::class, QueryFileNotFoundException::class, ReadOnlyException::class, RuntimeException::class] as $class) {
            $exception = new $class();
            $this->assertInstanceOf(\LogicException::class, $exception);
            $this->assertInstanceOf(ExceptionInterface::class, $exception);
        }
    }
}
