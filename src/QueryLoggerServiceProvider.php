<?php

namespace Dottedai\QueryLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Events\RouteMatched;

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

                // Log the query to Laravel's log file
                Log::debug("Query: {$sql} [{$time} ms]");

                // Output the query to the console when using the built-in server
                if (php_sapi_name() === 'cli-server') {
                    error_log("Query: {$sql} [{$time} ms]");
                }
            });

            // Listen for when a route is matched
            $this->app['events']->listen(RouteMatched::class, function (RouteMatched $event) {
                $route = $event->route;
                $uri = $route->uri();
                $action = $route->getAction();
                $controller = $action['controller'] ?? 'Closure';
                $middleware = $route->gatherMiddleware();

                $msg = "Route: {$uri} | Controller: {$controller}";
                if (!empty($middleware)) {
                    $msg .= " | Middleware: " . implode(', ', $middleware);
                }

                // Log the route information to Laravel's log file
                Log::debug($msg);

                // Output the route info to the console when using the built-in server
                if (php_sapi_name() === 'cli-server') {
                    error_log($msg);
                }
            });
        }
    }

    public function register()
    {
        // Additional service registrations if needed.
    }
}
