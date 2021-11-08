<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Ray\Di\Di\Inject;

trait QueryLocatorInject
{
    /**
     * @var QueryLocatorInterface
     */
    protected $query;

    /**
     * @Ray\Di\Di\Inject
     */
    #[Inject]
    public function setQueryLocator(QueryLocatorInterface $query)
    {
        $this->query = $query;
    }
}
