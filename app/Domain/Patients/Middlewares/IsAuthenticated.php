<?php

namespace App\Domain\Patients\Middlewares;

use App\Domain\Patients\PatientAuthRepositoryContract;
use Closure;
use Illuminate\Http\Request;

class IsAuthenticated
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
        $repo = app(PatientAuthRepositoryContract::class);

        if (!$repo->isLoggedIn()) {
            return $repo->getNotLoggedInResponse();
        }

        return $next($request);
    }
}
