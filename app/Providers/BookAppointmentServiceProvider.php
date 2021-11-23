<?php

namespace App\Providers;

use App\Domain\BookAppointment\AnandamidaRepository;
use App\Domain\BookAppointment\FisioNextRepository;
use App\Domain\BookAppointment\FisioLegacyRepository;

use App\Domain\BookAppointment\RepositoryContract;
use Illuminate\Support\ServiceProvider;

class BookAppointmentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        switch (env('BOOK_APPOINTMENTS_REPO', 'next')) {
            case 'next':
                $this->app->bind(RepositoryContract::class, FisioNextRepository::class);
                break;
            case 'legacy':
                $this->app->bind(RepositoryContract::class, FisioLegacyRepository::class);
                break;
            case 'anandamida':
                $this->app->bind(RepositoryContract::class, AnandamidaRepository::class);
                break;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
