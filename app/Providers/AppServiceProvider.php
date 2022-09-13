<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\StageRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\EloquentProjectRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\EloquentMemberRepository;
use App\Repositories\EloquentTaskRepository;
use App\Repositories\EloquentStageRepository;
use App\Repositories\EloquentRoleRepository;
use App\Repositories\EloquentCommentRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $file = app_path('Helpers/helpers.php');
        if (file_exists($file))
        {
            require_once($file);
        }
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

        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);

        $this->app->bind(StageRepositoryInterface::class, EloquentStageRepository::class);

        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);

        $this->app->bind(CommentRepositoryInterface::class, EloquentCommentRepository::class);
    }
}
