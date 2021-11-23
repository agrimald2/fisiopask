<?php

namespace App\Http\Controllers\GoogleCalendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class GoogleCalendarController extends Controller
{
    public function connect()
    {
        $url = calendar()->getGooglePermissionUrl(
            route('google.calendar.connected')
        );

        if (request()->inertia()) {
            return Inertia::location($url);
        }

        return redirect()->away($url);
    }


    public function disconnect(Request $request)
    {
        $user = $request->user();
        $user->google_access_token = null;
        $user->save();

        if (request()->inertia()) {
            return Inertia::location(route('profile.show'));
        }

        return redirect()->route('profile.show');
    }


    public function connected(Request $request)
    {
        $code = $request->code;
        $scope = $request->scope;

        $accessToken = calendar()->getAccessTokenFromAuthorizationCode($code);

        abort_if(
            Arr::has($accessToken, 'error'),
            400,
            response()->json($accessToken)
        );

        if ($accessToken) {
            $user = $request->user();
            $user->google_access_token = json_encode($accessToken);
            $user->save();
        }

        return redirect()->route('profile.show');
    }
}
