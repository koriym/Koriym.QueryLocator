<?php
/**
 * This file is part of the Koriym.QueryLocator
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Koriym\QueryLocator;

interface QueryProviderInterface
{
    /**
     * @return string
     */
    public function get();
}
