<?php

namespace App\Providers;

use App\Models\PasswordReset;
use App\Repositories\DB\PasswordReset\DBPasswordResetRepository;
use App\Repositories\DB\User\DBUserRepository;
use App\Repositories\DB\UserSecurityLogs\DBUserSecurityLogsRepository;use App\Repositories\PasswordResetRepository;use App\Repositories\UserRepository;use App\Repositories\UserSecurityLogsRepository;use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepository::class,DBUserRepository::class);
        $this->app->bind(UserSecurityLogsRepository::class,DBUserSecurityLogsRepository::class);
        $this->app->bind(PasswordResetRepository::class,DBPasswordResetRepository::class);
    }
}
