<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

trait QueryLocatorInject
{
    /**
     * @var QueryLocatorInterface
     */
    protected $query;

    /**
     * @Ray\Di\Di\Inject
     */
    public function setQueryLocator(QueryLocatorInterface $query)
    {
        $this->query = $query;
    }
}
