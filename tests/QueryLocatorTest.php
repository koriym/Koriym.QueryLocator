<?php

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;

class QueryLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryLocator
     */
    protected $query;

    protected function setUp()
    {
        parent::setUp();
        $sqlDir = __DIR__ . '/sql';
        $this->query = new QueryLocator($sqlDir);
    }

    public function testGetSql()
    {
        $sql = $this->query->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetSqlCached()
    {
        $sql = $this->query->get('admin/user');
        $sql = $this->query->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testArrayAccess()
    {
        $sql = $this->query['admin/user'];
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testNotFound()
    {
        $this->setExpectedException(QueryFileNotFoundException::class);
        $this->query['user/not_exist_sql'];
    }

    public function testGetCountSql()
    {
        $sql = $this->query->getCountQuery('admin/user');
        $expected = 'SELECT COUNT(*) FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlCached()
    {
        $sql = $this->query->getCountQuery('admin/user');
        $sql = $this->query->getCountQuery('admin/user');
        $expected = 'SELECT COUNT(*) FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlFailed()
    {
        $this->setExpectedException(CountQueryException::class);
        $this->query->getCountQuery('admin/distinct');
    }

    public function testOffsetExists()
    {
        $isSet = isset($this->query['admin/user']);
        $this->assertTrue($isSet);
    }

    public function testOffsetUnset()
    {
        $this->setExpectedException(ReadOnlyException::class);
        unset($this->query['admin/user']);
    }

    public function testSet()
    {
        $this->setExpectedException(ReadOnlyException::class);
        $this->query['admin/user'] = 'A SQL';
    }
}
