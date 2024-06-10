# Koriym.QueryLocator

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/badges/quality-score.png?b=php8.1-support)](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/)
[![codecov](https://codecov.io/gh/koriym/Koriym.QueryLocator/graph/badge.svg?token=WLZIl7jcaK)](https://codecov.io/gh/koriym/Koriym.QueryLocator)
[![Continuous Integration](https://github.com/koriym/Koriym.QueryLocator/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/koriym/Koriym.QueryLocator/actions/workflows/continuous-integration.yml)

## Installation

### Composer install

    $ composer require koriym/query-locator

### Usage

```php
use Koriym\QueryLocator\QueryLocator;

$query = new QueryLocator($sqlDir);
$sql = $query['admin/user'];                // SELECT * FROM user;
$sql = $query->getCountQuery('admin/user'); // SELECT COUNT(*) FROM user;
```

SQL files

```text
└── sql
    └── admin
        └── user.sql
```
