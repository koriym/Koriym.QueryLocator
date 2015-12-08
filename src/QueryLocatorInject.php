<?php
/**
 * This file is part of the Koriym.QueryLocator
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Koriym\QueryLocator;

use Koriym\QueryLocator\QueryLocatorInterface;

trait QueryLocatorInject
{
    /**
     * @var QueryLocatorInterface
     */
    private $query;

    /**
     * @Ray\Di\Di\Inject
     */
    public function setQueryLocator(QueryLocatorInterface $query)
    {
        $this->query = $query;
    }
}
