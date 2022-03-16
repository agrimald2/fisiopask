<?php

if (!function_exists('toast')) {
    /**
     * Send a toast notification to frontend
     *
     * @param string $type success|danger|info
     * @param string $message notification message
     * @return void
     */
    function toast($type, $message)
    {
        $flash = session('flash', []);

        array_push($flash, [$type, $message]);

        return session([
            'flash' => $flash
        ]);
    }
}


if (!function_exists('doctors')) {
    /**
     * @return App\Services\DoctorService
     */
    function doctors()
    {
        return app(App\Services\DoctorService::class);
    }
}


if (!function_exists('reniec')) {

    /**
     * @return App\Domain\Reniec\Datas\PatientData
     */
    function reniec($dni)
    {
        return app(App\Domain\Reniec\ReniecService::class)->get($dni);
    }
}


if (!function_exists('schedules')) {
    /**
     * @return App\Services\ScheduleService
     */
    function schedules()
    {
        return app(App\Services\ScheduleService::class);
    }
}


if (!function_exists('calendar')) {
    /**
     * @return App\Domain\GoogleCalendar\Auth
     */
    function calendar()
    {
        return app(App\Domain\GoogleCalendar\Auth::class);
    }
}


if (!function_exists('patients')) {
    /**
     * @return App\Services\PatientService
     */
    function patients()
    {
        return app(App\Services\PatientService::class);
    }
}


if (!function_exists('chatapi')) {
    function chatapi($phone, $text)
    {
        if ($phone && $text) {
            $chatApi = app(App\Services\ChatApiService::class);
            return $chatApi->send($phone, $text);
        }
        return null;
    }
}


if (!function_exists('offices')) {
    /**
     * @return App\Services\OfficeService
     */
    function offices()
    {
        return app(App\Services\OfficeService::class);
    }
}


if (!function_exists('workspaces')) {
    /**
     * @return App\Services\WorkspaceService
     */
    function workspaces()
    {
        return app(App\Services\WorkspaceService::class);
    }
}

if (!function_exists('recommendations')) {
    /**
     * @return App\Services\RecommendationService
     */
    function recommendations()
    {
        return app(App\Services\RecommendationService::class);
    }
}


if (!function_exists('appointments')) {
    /**
     * @return App\Services\AppointmentService
     */
    function appointments()
    {
        return app(App\Services\AppointmentService::class);
    }
}


if (!function_exists('doctorSpecialties')) {
    /**
     * @return App\Services\DoctorSpecialtyService
     */
    function doctorSpecialties()
    {
        return app(App\Services\DoctorSpecialtyService::class);
    }
}


if (!function_exists('carbon_parse')) {
    function carbon_parse($date)
    {
        return \Carbon\Carbon::parse($date);
    }
}
