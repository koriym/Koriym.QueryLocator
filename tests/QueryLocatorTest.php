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
        $sqlDir = __DIR__ . '/Fake/Sql';
        $this->query = new QueryLocator($sqlDir);
    }

    public function testGetSql()
    {
        $sql = $this->query->get('user/select_user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetSqlCached()
    {
        $sql = $this->query->get('user/select_user');
        $sql = $this->query->get('user/select_user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testArrayAccess()
    {
        $sql = $this->query['user/select_user'];
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
        $sql = $this->query->getCountQuery('user/select_user');
        $expected = 'SELECT COUNT(*) FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlCached()
    {
        $sql = $this->query->getCountQuery('user/select_user');
        $sql = $this->query->getCountQuery('user/select_user');
        $expected = 'SELECT COUNT(*) FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testGetCountSqlFailed()
    {
        $this->setExpectedException(CountQueryException::class);
        $this->query->getCountQuery('user/distinct');
    }

    public function testOffsetExists()
    {
        $isSet = isset($this->query['user/select_user']);
        $this->assertTrue($isSet);
    }

    public function testOffsetUnset()
    {
        $this->setExpectedException(ReadOnlyException::class);
        unset($this->query['user/select_user']);
    }

    public function testSet()
    {
        $this->setExpectedException(ReadOnlyException::class);
        $this->query['user/select_user'] = 'A SQL';
    }
}
