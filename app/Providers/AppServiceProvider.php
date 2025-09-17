<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\FileTaskRepository;
use App\Repositories\TaskRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{   
    public function register(): void
    {
        $this->app->bind(
            TaskRepositoryInterface::class,
            FileTaskRepository::class
        );
    }

    public function boot(): void
    {
        
    }
} 