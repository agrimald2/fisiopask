<?php

namespace App\Providers;

use App\Domain\Patients\DefaultRepository;
use App\Domain\Patients\PatientAuthRepositoryContract;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(PatientAuthRepositoryContract::class, DefaultRepository::class);
    }
}
