# Laravel Query Logger

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dottedai/laravel-query-logger.svg)](https://packagist.org/packages/dottedai/laravel-query-logger)
[![License](https://img.shields.io/packagist/l/dottedai/laravel-query-logger.svg)](https://packagist.org/packages/dottedai/laravel-query-logger)

A simple Laravel package to log and display database queries similar to Rails' console output. This tool is intended for use in local development environments, making it easier to debug and optimize your SQL queries.

## Features

- **Automatic Query Logging:** Listens to all database queries executed by your Laravel application.
- **Log File Integration:** Records each query, including execution time, into Laravel's default log file (`storage/logs/laravel.log`).
- **Browser Console Output:** Optionally outputs queries to the browser's JavaScript console for quick inspection.
- **Route Logging:** Logs route details—including the URI, controller, and middleware—to provide a complete view of the request lifecycle.
- **Console Info Logging:** Displays `Log::info` (and higher level) messages in the terminal (when using `php artisan serve`) by adding a custom Monolog handler.
- **Development Focused:** Designed to work in the local environment to aid debugging without impacting production performance.

## Requirements

- PHP >= 7.2
- Laravel 6.x, 7.x, 8.x, 9.x, or 10.x

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

Once installed and registered, the package will automatically begin listening for database queries, route events, and log messages when your application is running in the local environment.

## How It Works

### Query Listener
The package uses Laravel's `DB::listen` method to capture all SQL queries, replaces query placeholders with their actual binding values, and logs the query along with its execution time.

### Route Logging
It listens for Laravel's `RouteMatched` event to log route details such as the URI, controller handling the request, and any associated middleware.

### Console Output
When using the built-in PHP server (`php artisan serve`), queries and route logs are output to the terminal using PHP's `error_log()` function.

### Console Info Logging
A custom Monolog handler is added to also output `Log::info` (and higher-level) messages to the terminal. This means that any log calls such as `Log::info`, `Log::warning`, or `Log::error` will appear in your console when running locally.

## Example Output

When a query is executed and a route is matched, you might see output similar to this in your log file and terminal:

```
Query: SELECT * FROM users WHERE email = 'example@example.com' [12 ms]
Route: api/users | Controller: App\Http\Controllers\UserController@index | Middleware: api, auth
INFO: Application started successfully.
```

## Customization

You can modify the behavior of the query logger by editing the `QueryLoggerServiceProvider` class. For example, you might choose to disable the JavaScript console output, adjust the logging level, or customize the route logging format to fit your needs.

## Important Considerations

### Development Use Only
This package is meant for local development environments. Ensure it is disabled or removed from production to prevent potential performance issues or the inadvertent exposure of sensitive query data.

### Security
Logging SQL queries and other sensitive application data can expose critical information. Always use this tool only in safe, non-production environments.

## Contributing

Contributions, suggestions, and bug reports are welcome! Feel free to submit an issue or pull request on GitHub.

## License

This package is open-sourced software licensed under the MIT license.