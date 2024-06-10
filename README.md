# Koriym.QueryLocator

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/badges/quality-score.png?b=php8.1-support)](https://scrutinizer-ci.com/g/koriym/Koriym.QueryLocator/)
[![codecov](https://codecov.io/gh/koriym/Koriym.QueryLocator/graph/badge.svg?token=WLZIl7jcaK)](https://codecov.io/gh/koriym/Koriym.QueryLocator)
[![Continuous Integration](https://github.com/koriym/Koriym.QueryLocator/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/koriym/Koriym.QueryLocator/actions/workflows/continuous-integration.yml)

**Koriym.QueryLocator** is a PHP library that helps you manage SQL queries by locating and loading them from the file system. This approach simplifies query management and enhances code readability.

## Installation

Install the library using Composer:

```sh
$ composer require koriym/query-locator
```

## Usage

### Basic Example

To use the QueryLocator class, instantiate it with the directory where your SQL files are stored. You can then retrieve queries using keys that correspond to the directory structure.

**SQL Files Directory Structure**

```text
└── sql
    └── admin
        └── user.sql
```
**Code Example**

```php
use Koriym\QueryLocator\QueryLocator;

// Define the directory where your SQL files are stored
$sqlDir = 'path/to/sql/files';

// Instantiate the QueryLocator
$query = new QueryLocator($sqlDir);

// Retrieve a query
$sql = $query['admin/user']; // This will load the contents of 'admin/user.sql'

// Retrieve a count query
$countSql = $query->getCountQuery('admin/user'); // This will generate 'SELECT COUNT(*) FROM user'
```

## Features

- **File-Based Query Management**: Store your SQL queries in separate files for better organization.
- **Simple Query Retrieval**: Use directory-based keys to retrieve queries.
- **Count Query Generation**: Automatically generate count queries.

## Benefits

- **Improved Readability**: Keep your PHP code clean and readable by moving SQL queries to dedicated files.
- **Easy Maintenance**: Modify your SQL queries without changing the PHP code, just update the SQL files.
- **Structured Organization**: Organize your queries in a directory structure that makes sense for your application.
