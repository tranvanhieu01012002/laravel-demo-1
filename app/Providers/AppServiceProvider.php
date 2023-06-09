<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->bind(
            \App\Services\Auth\ThirdParty\IAuthService::class,
            \App\Services\Auth\ThirdParty\GoogleAuthService::class,
        );

        $this->app->bind(
            \App\Services\Room\IRoomService::class,
            \App\Services\Room\RoomService::class
        );

        $this->app->bind(
            \App\Services\Question\IQuestionService::class,
            \App\Services\Question\QuestionService::class
        );

        $this->app->bind(
            \App\Services\Question\ISetQuestionService::class,
            \App\Services\Question\SetQuestionService::class
        );

        $this->app->bind(
            \App\Services\User\IUserService::class,
            \App\Services\User\UserService::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
