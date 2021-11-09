<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\ReadOnlyException;
use ReturnTypeWillChange;
use function is_string;

final class ApcQueryLocator implements QueryLocatorInterface
{
    /**
     * @var QueryLocator
     */
    private $query;

    /**
     * Cache prefix
     *
     * @var string
     */
    private $nameSpace;

    public function __construct(string $sqlDir, string $nameSpace)
    {
        $this->nameSpace = $nameSpace . '-';
        $this->query = new QueryLocator($sqlDir);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $queryName) : string
    {
        $sqlId = $this->nameSpace . $queryName;
        /** @var ?string $sql */
        $sql = apcu_fetch($sqlId);
        if (is_string($sql)) {
            return $sql; // @codeCoverageIgnore
        }
        $sql = $this->query->get($queryName);
        apcu_store($sqlId, $sql);

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQuery(string $queryName) : string
    {
        $sqlId = $this->nameSpace . $queryName;
        /** @var ?string $sql */
        $sql = apcu_fetch($sqlId);
        if (is_string($sql)) {
            return $sql; // @codeCoverageIgnore
        }
        $sql = $this->query->getCountQuery($queryName);
        apcu_store($sqlId, $sql);

        return $sql;
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
}
