<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Contracts\StorageContract::class, \App\Repositories\StorageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {           
        DB::listen(function(QueryExecuted $queryExecuted) {
            Log::info('Query Executed', [
                'time' => $queryExecuted->time,
                'query' => $queryExecuted->sql,
                'bindings' => $queryExecuted->bindings,
            ]);
        });
    }
}
