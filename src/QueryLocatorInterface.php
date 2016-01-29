<?php
/**
 * This file is part of the Koriym.QueryLocator
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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
