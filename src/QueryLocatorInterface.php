<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

interface QueryLocatorInterface extends \ArrayAccess
{
    /**
     * @param string $queryName
     *
     * @return string
     */
    public function get($queryName);

    /**
     * @param string $queryName
     *
     * @return string
     */
    public function getCountQuery($queryName);
}
