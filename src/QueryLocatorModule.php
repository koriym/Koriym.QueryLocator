<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use Override;
use Ray\Di\AbstractModule;

/** @psalm-api */
class QueryLocatorModule extends AbstractModule
{
    public function __construct(
        private readonly string $sqlDir,
        AbstractModule|null $module = null,
    ) {
        parent::__construct($module);
    }

    /**
     * No native return type: subclasses overriding configure() without one must stay compatible.
     *
     * @return void
     */
    #[Override]
    protected function configure()
    {
        $this->bind()->annotatedWith('sql_dir')->toInstance($this->sqlDir);
        $this->bind(QueryLocatorInterface::class)->toConstructor(QueryLocator::class, 'sqlDir=sql_dir');
    }
}
