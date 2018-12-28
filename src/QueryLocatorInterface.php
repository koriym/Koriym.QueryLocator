<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

interface QueryLocatorInterface extends \ArrayAccess
{
    /**
     * Get SQL
     */
    public function get(string $queryName) : string;

    /**
     * Get count query SQL
     */
    public function getCountQuery(string $queryName) : string;
}
