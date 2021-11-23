<?php

namespace App\Domain\ClinicHistories;

use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClinicHistoriesRepo::class);
        $this->app->singleton(Forms::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $repo = app(ClinicHistoriesRepo::class);
        
        //@note:HERE WE CAN ADD NEW FORMS
        $formLists = explode(',', trim(env('FORMS_ENABLED', 'anandamida,fisioRevision')));

        $repo->enableForms(
            $formLists
        );
    }
}
