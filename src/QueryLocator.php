<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;
use ReturnTypeWillChange;
use function is_string;

final class QueryLocator implements QueryLocatorInterface
{
    /**
     * @var string
     */
    private $sqlDir;

    public function __construct(string $sqlDir)
    {
        $this->sqlDir = $sqlDir;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $queryName) : string
    {
        $sqlFile = sprintf(
            '%s/%s.sql',
            $this->sqlDir,
            $queryName
        );

        return trim($this->getFileContents($sqlFile));
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQuery(string $queryName) : string
    {
        return $this->rewriteCountQuery($this->get($queryName));
    }

    /**
     * {@inheritdoc}
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        assert(is_string($offset));

        return (bool) $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        assert(is_string($offset));
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new ReadOnlyException('not supported');
    }

    /**
     * {@inheritdoc}
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new ReadOnlyException('not supported');
    }

    /**
     * Return count query
     *
     * @see https://github.com/pear/Pager/blob/master/examples/Pager_Wrapper.php
     * Taken from pear/pager and modified.
     * tested at https://github.com/pear/Pager/blob/80c0e31c8b94f913cfbdeccbe83b63822f42a2f8/tests/pager_wrapper_test.php#L19
     * @codeCoverageIgnore
     */
    private function rewriteCountQuery(string $sql) : string
    {
        if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $sql) || preg_match('/\s+GROUP\s+BY\s+/is', $sql)) {
            throw new CountQueryException($sql);
        }
        $openParenthesis = '(?:\()';
        $closeParenthesis = '(?:\))';
        $subQueryInSelect = $openParenthesis . '.*\bFROM\b.*' . $closeParenthesis;
        $pattern = '/.*%na' . $subQueryInSelect . 'me.*\bFROM\b\s+/Uims';
        if (preg_match($pattern, $sql)) {
                throw new CountQueryException($sql);
        }
        $subQueryWithLimitOrder = $openParenthesis . '.*\b(LIMIT|ORDER)\b.*' . $closeParenthesis;
        $pattern = '/.*\bFROM\b.*.*%na' . $subQueryWithLimitOrder . 'me.*.*/Uims';
        if (preg_match($pattern, $sql)) {
            throw new CountQueryException($sql);
        }
        $queryCount = preg_replace('/.*\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $sql, 1);
        if (! is_string($queryCount)) {
            throw new CountQueryException($sql);
        }
        $splitOrder = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
        if ($splitOrder === false) {
            throw new CountQueryException($sql);
        }
        $splitLimit = preg_split('/\bLIMIT\b/is', $splitOrder[0]);
        if ($splitLimit === false) {
            throw new CountQueryException($sql);
        }

        return trim($splitLimit[0]);
    }

    private function getFileContents(string $file) : string
    {
        if (! file_exists($file)) {
            throw new QueryFileNotFoundException($file);
        }
        $contents = file_get_contents($file);
        if (! $contents) {
            throw new QueryFileNotFoundException($file);
        }

        return $contents;
    }
}
