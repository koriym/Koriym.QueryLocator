<?php

declare(strict_types=1);

// In-memory APCu replacement for environments without ext-apcu.
if (! function_exists('apcu_fetch')) {
    final class ApcuStubStore
    {
        /** @var array<string, mixed> */
        public static array $values = [];
    }

    function apcu_fetch(string $key): mixed
    {
        return ApcuStubStore::$values[$key] ?? false;
    }

    function apcu_store(string $key, mixed $var): bool
    {
        ApcuStubStore::$values[$key] = $var;

        return true;
    }
}
