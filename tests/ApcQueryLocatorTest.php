<?php

declare(strict_types=1);

namespace Koriym\QueryLocator;

use function apcu_enabled;
use function apcu_fetch;
use function apcu_store;
use function function_exists;

class ApcQueryLocatorTest extends QueryLocatorTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $sqlDir = __DIR__ . '/sql';
        $this->query = new ApcQueryLocator($sqlDir, 'foo-namespace');
    }

    public function testStoresSqlUnderNamespacedKey(): void
    {
        $this->skipIfApcuDisabled();
        $query = new ApcQueryLocator(__DIR__ . '/sql', 'store-namespace');
        $query->get('admin/user');
        $query->getCountQuery('admin/user');
        $this->assertSame('SELECT * FROM usr;', apcu_fetch('store-namespace-admin/user'));
        $this->assertSame('SELECT COUNT(*) FROM usr;', apcu_fetch('Koriym\QueryLocator-sqlId-store-namespace-admin/user'));
    }

    public function testReturnsCachedSql(): void
    {
        $this->skipIfApcuDisabled();
        apcu_store('cached-namespace-admin/user', 'CACHED SQL');
        apcu_store('Koriym\QueryLocator-sqlId-cached-namespace-admin/user', 'CACHED COUNT SQL');
        $query = new ApcQueryLocator(__DIR__ . '/sql', 'cached-namespace');
        $this->assertSame('CACHED SQL', $query->get('admin/user'));
        $this->assertSame('CACHED SQL', $query['admin/user']);
        $this->assertSame('CACHED COUNT SQL', $query->getCountQuery('admin/user'));
    }

    private function skipIfApcuDisabled(): void
    {
        if (function_exists('apcu_enabled') && ! apcu_enabled()) {
            $this->markTestSkipped('APCu is disabled (apc.enable_cli=0)');
        }
    }
}
