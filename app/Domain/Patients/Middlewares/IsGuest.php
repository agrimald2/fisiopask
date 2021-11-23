<?php

namespace App\Domain\Patients\Middlewares;

use App\Domain\Patients\PatientAuthRepositoryContract;
use Closure;
use Illuminate\Http\Request;

class IsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var PatientAuthRepositoryContract
         */
        $repo = app(PatientAuthRepositoryContract::class);

        if ($repo->isLoggedIn()) {
            return $repo->getLoginAttemptSuccessResponse(
                $repo->getAuthenticatedPatient()
            );
        }

        return $next($request);
    }
}
