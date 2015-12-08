<?php
/**
 * This file is part of the Koriym.QueryLocator package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Koriym\QueryLocator;

use Ray\Di\AbstractModule;

class QueryLocatorModule extends AbstractModule
{
    private $sqlDir;

    public function __construct($sqlDir)
    {
        $this->sqlDir = $sqlDir;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind()->annotatedWith('sql_dir')->toInstance($this->sqlDir);
        $this->bind(QueryLocatorInterface::class)->toConstructor(QueryLocator::class, 'sqlDir=sql_dir');
    }
}
