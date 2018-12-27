<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Ray\Di\AbstractModule;

class QueryLocatorModule extends AbstractModule
{
    /**
     * @var string
     */
    private $sqlDir;

    /**
     * @param string $sqlDir
     */
    public function __construct($sqlDir, AbstractModule $module = null)
    {
        $this->sqlDir = $sqlDir;
        parent::__construct($module);
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
