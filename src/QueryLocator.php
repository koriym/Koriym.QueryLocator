<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\CountQueryException;
use Koriym\QueryLocator\Exception\QueryFileNotFoundException;
use Koriym\QueryLocator\Exception\ReadOnlyException;
use Override;

use function assert;
use function file_exists;
use function file_get_contents;
use function is_array;
use function is_string;
use function preg_match;
use function preg_replace;
use function preg_split;
use function sprintf;
use function trim;

final class QueryLocator implements QueryLocatorInterface
{
    public function __construct(
        private readonly string $sqlDir,
    ) {
    }

    #[Override]
    public function get(string $queryName): string
    {
        $sqlFile = sprintf(
            '%s/%s.sql',
            $this->sqlDir,
            $queryName,
        );

        return trim($this->getFileContents($sqlFile));
    }

    #[Override]
    public function getCountQuery(string $queryName): string
    {
        return $this->rewriteCountQuery($this->get($queryName));
    }

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return (bool) $this->get($offset);
    }

    #[Override]
    public function offsetGet(mixed $offset): string
    {
        return $this->get($offset);
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new ReadOnlyException('not supported');
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        throw new ReadOnlyException('not supported');
    }

    /**
     * Return count query
     *
     * Taken from pear/pager and modified.
     *
     * @see https://github.com/pear/Pager/blob/master/examples/Pager_Wrapper.php
     * @see https://github.com/pear/Pager/blob/80c0e31c8b94f913cfbdeccbe83b63822f42a2f8/tests/pager_wrapper_test.php#L19
     */
    private function rewriteCountQuery(string $sql): string
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
        assert(is_string($queryCount));
        $orderSplit = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
        assert(is_array($orderSplit));
        $limitSplit = preg_split('/\bLIMIT\b/is', $orderSplit[0]);
        assert(is_array($limitSplit));

        return trim($limitSplit[0]);
    }

    private function getFileContents(string $file): string
    {
        if (! file_exists($file)) {
            throw new QueryFileNotFoundException($file);
        }

        return (string) file_get_contents($file);
    }
}
