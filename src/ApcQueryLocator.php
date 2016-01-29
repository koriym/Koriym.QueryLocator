<?php
/**
 * This file is part of the Koriym.QueryLocator
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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

    public function __construct($sqlDir, $nameSpace)
    {
        $this->nameSpace = $nameSpace . '-';
        $this->sqlDir = $sqlDir;
        $this->query = new QueryLocator($sqlDir);
    }

    /**
     * {@inheritdoc}
     */
    public function get($queryName)
    {
        $sqlId = $this->nameSpace . $queryName;
        $sql = apc_fetch($sqlId);
        if ($sql !== false) {
            return $sql;
        }
        $sql = $this->query->get($queryName);
        apc_store($sqlId, $sql);

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQuery($queryName)
    {
        $sqlId = $this->nameSpace . $queryName;
        $sql = apc_fetch($sqlId);
        if ($sql !== false) {
            return $sql;
        }
        $sql = $this->query->getCountQuery($queryName);
        apc_store($sqlId, $sql);

        return $sql;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return (bool) $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new ReadOnlyException('not supported');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new ReadOnlyException('not supported');
    }
}
