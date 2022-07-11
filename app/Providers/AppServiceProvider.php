<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Repositories\EloquentProjectRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\EloquentMemberRepository;

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
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);

        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        $this->app->bind(MemberRepositoryInterface::class, EloquentMemberRepository::class);
    }
}
