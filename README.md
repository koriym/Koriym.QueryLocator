# Koriym.QueryLocator

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/badges/quality-score.png?b=1.x)](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/?branch=1.x)
[![Code Coverage](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/badges/coverage.png?b=1.x)](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/?branch=1.x)
[![Build Status](https://travis-ci.org/koriym/Koriym.QueryLocator.svg?branch=1.x)](https://travis-ci.org/koriym/Koriym.QueryLocator)

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
```
└── sql
    └── admin
        └── user.sql
```
 
