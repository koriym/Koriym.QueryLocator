<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Ray\Di\Di\Inject;

/**
 * The property and setter stay untyped: classes that use this trait may declare or override them.
 */
trait QueryLocatorInject
{
    /** @var QueryLocatorInterface */
    protected $query;

    /**
     * @return void
     *
     * @Ray\Di\Di\Inject
     */
    #[Inject]
    public function setQueryLocator(QueryLocatorInterface $query)
    {
        $this->query = $query;
    }
}
