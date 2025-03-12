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

                // If using the built-in server, print to the terminal's error log so it shows in the console
                if (php_sapi_name() === 'cli-server') {
                    error_log("Query: {$sql} [{$time} ms]");
                }
            });
        }
    }

    public function register()
    {
        // Additional bindings or service registrations...
    }
}
