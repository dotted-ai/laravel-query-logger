<?php

namespace Dottedai\QueryLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Events\RouteMatched;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

class QueryLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Only enable logging in the local environment
        if ($this->app->environment('local')) {

            // Log every database query
            DB::listen(function ($query) {
                $sql = $query->sql;
                $bindings = $query->bindings;
                $time = $query->time;

                // Replace placeholders with actual binding values
                foreach ($bindings as $binding) {
                    $value = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
                    $sql = preg_replace('/\?/', $value, $sql, 1);
                }

                $queryMessage = "Query: {$sql} [{$time} ms]";

                // Log the query to Laravel's log file
                Log::debug($queryMessage);

                // Output the query to the console when using the built-in server
                if (php_sapi_name() === 'cli-server') {
                    error_log($queryMessage);
                }
            });

            // Listen for when a route is matched
            $this->app['events']->listen(RouteMatched::class, function (RouteMatched $event) {
                $route = $event->route;
                $uri = $route->uri();
                $action = $route->getAction();
                $controller = $action['controller'] ?? 'Closure';
                $middleware = $route->gatherMiddleware();

                $routeMessage = "Route: {$uri} | Controller: {$controller}";
                if (!empty($middleware)) {
                    $routeMessage .= " | Middleware: " . implode(', ', $middleware);
                }

                // Log the route information to Laravel's log file
                Log::debug($routeMessage);

                // Output the route info to the console when using the built-in server
                if (php_sapi_name() === 'cli-server') {
                    error_log($routeMessage);
                }
            });

            // Add a custom Monolog handler to also output Log::info messages to the console.
            if (php_sapi_name() === 'cli-server') {
                $logger = Log::getLogger();
                // Correct parameter order: (messageType, level, bubble)
                $logger->pushHandler(new \Monolog\Handler\ErrorLogHandler(
                    \Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM,
                    \Monolog\Logger::INFO,
                    true
                ));
            }            
        }
    }

    public function register()
    {
        // Additional service registrations if needed.
    }
}
