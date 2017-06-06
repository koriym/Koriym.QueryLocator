<?php
/**
 * This file is part of the Koriym.QueryLocator
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;

final class QueryLocator implements QueryLocatorInterface
{
    /**
     * @var string
     */
    private $sqlDir;

    /**
     * @param string $sqlDir
     */
    public function __construct($sqlDir)
    {
        $this->sqlDir = $sqlDir;
    }

    /**
     * {@inheritdoc}
     */
    public function get($queryName)
    {
        $sqlFile = sprintf(
            '%s/%s.sql',
            $this->sqlDir,
            $queryName
        );
        if (! file_exists($sqlFile)) {
            throw new QueryFileNotFoundException($queryName);
        }
        $sql = trim(file_get_contents($sqlFile));

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQuery($queryName)
    {
        $countSql = $this->rewriteCountQuery($this->get($queryName));

        return $countSql;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return (bool) $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new ReadOnlyException('not supported');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new ReadOnlyException('not supported');
    }

    /**
     * Return count query
     *
     * @param string $sql
     *
     * @return string
     *
     * @see https://github.com/pear/Pager/blob/master/examples/Pager_Wrapper.php
     * Taken from pear/pager and modified.
     * tested at https://github.com/pear/Pager/blob/80c0e31c8b94f913cfbdeccbe83b63822f42a2f8/tests/pager_wrapper_test.php#L19
     * @codeCoverageIgnore
     */
    private function rewriteCountQuery($sql)
    {
        if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $sql) || preg_match('/\s+GROUP\s+BY\s+/is', $sql)) {
            throw new CountQueryException($sql);
        }
        $openParenthesis = '(?:\()';
        $closeParenthesis = '(?:\))';
        $subQueryInSelect = $openParenthesis . '.*\bFROM\b.*' . $closeParenthesis;
        $pattern = '/(?:.*' . $subQueryInSelect . '.*)\bFROM\b\s+/Uims';
        if (preg_match($pattern, $sql)) {
            throw new CountQueryException($sql);
        }
        $subQueryWithLimitOrder = $openParenthesis . '.*\b(LIMIT|ORDER)\b.*' . $closeParenthesis;
        $pattern = '/.*\bFROM\b.*(?:.*' . $subQueryWithLimitOrder . '.*).*/Uims';
        if (preg_match($pattern, $sql)) {
            throw new CountQueryException($sql);
        }
        $queryCount = preg_replace('/(?:.*)\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $sql, 1);
        list($queryCount) = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
        list($queryCount) = preg_split('/\bLIMIT\b/is', $queryCount);

        return trim($queryCount);
    }
}
