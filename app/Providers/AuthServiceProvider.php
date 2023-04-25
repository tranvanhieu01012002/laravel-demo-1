<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Question;
use App\Models\SetQuestion;
use App\Policies\QuestionPolicy;
use App\Policies\SetQuestionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        SetQuestion::class => SetQuestionPolicy::class,
        Question::class => QuestionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
