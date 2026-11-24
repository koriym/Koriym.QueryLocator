<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use MyVendor\MyPacakge\FakeQueryConsumer;
use MyVendor\MyPacakge\RegisterUser;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class QueryLocatorModuleTest extends TestCase
{
    public function testGetSql(): void
    {
        $queryLocator = (new Injector(new QueryLocatorModule(__DIR__ . '/sql')))->getInstance(QueryLocatorInterface::class);
        $this->assertInstanceOf(QueryLocatorInterface::class, $queryLocator);
        $sql = $queryLocator->get('admin/user');
        $expected = 'SELECT * FROM usr;';
        $this->assertSame($expected, $sql);
    }

    public function testBindsQueryLocator(): void
    {
        $queryLocator = (new Injector(new QueryLocatorModule(__DIR__ . '/sql')))->getInstance(QueryLocatorInterface::class);
        $this->assertInstanceOf(QueryLocator::class, $queryLocator);
    }

    public function testBindsSqlDir(): void
    {
        $sqlDir = (new Injector(new QueryLocatorModule(__DIR__ . '/sql')))->getInstance('', 'sql_dir');
        $this->assertSame(__DIR__ . '/sql', $sqlDir);
    }

    public function testInstallsGivenModule(): void
    {
        $module = new class extends AbstractModule {
            protected function configure(): void
            {
                $this->bind(QueryProviderInterface::class)->to(RegisterUser::class);
            }
        };
        $injector = new Injector(new QueryLocatorModule(__DIR__ . '/sql', $module));
        $provider = $injector->getInstance(QueryProviderInterface::class);
        $this->assertInstanceOf(QueryProviderInterface::class, $provider);
        $this->assertSame('REGISTER-USER-SQL', $provider->get());
        $this->assertInstanceOf(QueryLocator::class, $injector->getInstance(QueryLocatorInterface::class));
    }

    public function testQueryLocatorInject(): void
    {
        $module = new class extends AbstractModule {
            protected function configure(): void
            {
                $this->bind(FakeQueryConsumer::class);
            }
        };
        $consumer = (new Injector(new QueryLocatorModule(__DIR__ . '/sql', $module)))->getInstance(FakeQueryConsumer::class);
        $this->assertInstanceOf(FakeQueryConsumer::class, $consumer);
        $this->assertSame('SELECT * FROM usr;', $consumer->getSql('admin/user'));
    }
}
