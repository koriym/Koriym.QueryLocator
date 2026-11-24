<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\ReadOnlyException;
use Override;

use function apcu_fetch;
use function apcu_store;
use function is_string;

final class ApcQueryLocator implements QueryLocatorInterface
{
    private readonly QueryLocator $query;

    /** Cache key prefix */
    private readonly string $nameSpace;

    public function __construct(string $sqlDir, string $nameSpace)
    {
        $this->nameSpace = $nameSpace . '-';
        $this->query = new QueryLocator($sqlDir);
    }

    #[Override]
    public function get(string $queryName): string
    {
        $sqlId = $this->nameSpace . $queryName;
        /** @var mixed $sql */
        $sql = apcu_fetch($sqlId);
        if (is_string($sql)) {
            return $sql;
        }

        $sql = $this->query->get($queryName);
        apcu_store($sqlId, $sql);

        return $sql;
    }

    #[Override]
    public function getCountQuery(string $queryName): string
    {
        $sqlId = $this->nameSpace . $queryName;
        $apcuId = __NAMESPACE__ . '-sqlId-' . $sqlId;
        /** @var mixed $sql */
        $sql = apcu_fetch($apcuId);
        if (is_string($sql)) {
            return $sql;
        }

        $sql = $this->query->getCountQuery($queryName);
        apcu_store($apcuId, $sql);

        return $sql;
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
}
