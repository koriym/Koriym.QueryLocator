<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Koriym\QueryLocator\Exception\ReadOnlyException;

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

    /**
     * SQL directory
     *
     * @var string
     */
    private $sqlDir;

    public function __construct(string $sqlDir, string $nameSpace)
    {
        $this->nameSpace = $nameSpace . '-';
        $this->sqlDir = $sqlDir;
        $this->query = new QueryLocator($sqlDir);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $queryName) : string
    {
        $sqlId = $this->nameSpace . $queryName;
        $sql = apcu_fetch($sqlId);
        if ($sql !== false) {
            return $sql;
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
        $sql = apcu_fetch($sqlId);
        if (\is_string($sql)) {
            return $sql;
        }
        $sql = $this->query->getCountQuery($queryName);
        apcu_store($sqlId, $sql);

        return $sql;
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
}
