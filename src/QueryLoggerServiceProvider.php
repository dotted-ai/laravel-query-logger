<?php

namespace Dottedai\QueryLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Only enable query logging in the local environment
        if ($this->app->environment('local')) {
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

                // Optionally, output the query to the browser's console
                if (!app()->runningInConsole()) {
                    echo "<script>console.log('Query: " . addslashes($sql) . " [{$time} ms]');</script>";
                }
            });
        }
    }

    public function register()
    {
        // You can register additional services or bindings here if needed.
    }
}
