<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('adminUser', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('viewOnlyGuestProject', function (User $user, Project $project) {
            return $user->isAdmin() || $project->id == $user->project_id;
        });
    }
}
