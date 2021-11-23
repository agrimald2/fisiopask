<?php

namespace App\Domain\GoogleCalendar;


interface AuthContract
{
    public function getCalendarFromAccessToken($accessToken);

    public function getGooglePermissionUrl();

    public function getAccessTokenFromAuthorizationCode($code);
}
