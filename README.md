# Laravel Query Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dottedai/laravel-query-logger.svg)](https://packagist.org/packages/dottedai/laravel-query-logger)
[![License](https://img.shields.io/packagist/l/dottedai/laravel-query-logger.svg)](https://packagist.org/packages/dottedai/laravel-query-logger)

A simple Laravel package to log and display database queries similar to Rails' console output. This tool is intended for use in local development environments, making it easier to debug and optimize your SQL queries.

## Features

- **Automatic Query Logging:** Listens to all database queries executed by your Laravel application.
- **Log File Integration:** Records each query, including execution time, into Laravel's default log file (`storage/logs/laravel.log`).
- **Browser Console Output:** Optionally outputs queries to the browser's JavaScript console for quick inspection.
- **Development Focused:** Designed to work in the local environment to aid debugging without impacting production performance.

## Requirements

- PHP >= 7.2
- Laravel 6.x, 7.x, 8.x, or 9.x

## Installation

You can install the package via Composer:

```bash
composer require dottedai/laravel-query-logger
```

Laravel's package auto-discovery will automatically register the service provider. If you need to manually register it, add the following to your `config/app.php` file within the providers array:

```php
Dottedai\QueryLogger\QueryLoggerServiceProvider::class,
```

## Usage

Once installed and registered, the package will automatically begin listening for database queries if your application is running in the local environment.

### How It Works

1. **Query Listener**: The package uses Laravel's `DB::listen` method to capture all SQL queries.
2. **Binding Replacement**: It replaces the query placeholders with actual binding values.
3. **Logging**: Each query, along with its execution time, is logged using Laravel's logging system.
4. **Console Output**: When not running in the console, queries are output to the browser's JavaScript console.

### Example Output

When a query is executed, you might see output similar to this in your log file and browser console:

```
Query: SELECT * FROM users WHERE email = 'example@example.com' [12 ms]
```

## Customization

You can modify the behavior of the query logger by editing the `QueryLoggerServiceProvider` class. For example, you might choose to disable the JavaScript console output or change the logging level based on your specific needs.

## Important Considerations

- **Development Use Only**: This package is meant for local development environments. Make sure to disable or remove it from production to prevent potential performance issues or the inadvertent exposure of sensitive query data.
- **Security**: Logging SQL queries can expose sensitive data. Always ensure this tool is only active in safe, non-production environments.

## Contributing

Contributions, suggestions, and bug reports are welcome! Feel free to submit an issue or pull request on GitHub.

## License

This package is open-sourced software licensed under the MIT license.