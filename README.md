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
$sql = $query['user/select_user'];                // SELECT * FROM usr;
$sql = $query->getCountQuery('user/select_user'); // SELECT COUNT(*) FROM usr;
```

SQL files
```
└── Sql
    └── User
        └── select_user.sql
```

## Requirements

 * PHP 5.5+
 * hhvm
 
