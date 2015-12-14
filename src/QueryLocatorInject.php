<?php
/**
 * This file is part of the Koriym.QueryLocator
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Koriym\QueryLocator;

trait QueryLocatorInject
{
    /**
     * @var QueryLocatorInterface
     */
    protected $query;

    /**
     * @param QueryLocatorInterface $query
     *
     * @Ray\Di\Di\Inject
     */
    public function setQueryLocator(QueryLocatorInterface $query)
    {
        $this->query = $query;
    }
}
