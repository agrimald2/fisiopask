<?php

namespace App\Domain\GoogleCalendar;


use Google_Client;
use Google_Service_Calendar;

class Auth implements AuthContract
{
    use Helpers;

    protected $client;

    public function __construct(Google_Client $client)
    {
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        $client->setAuthConfig(base_path('credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $this->client = $client;
    }


    /**
     * Set acces_token.json parsed to array
     *
     * @param array $accessToken
     * @return Google_Service_Calendar
     */
    public function getCalendarFromAccessToken($accessToken)
    {
        if (!$accessToken) return null;

        $this->client->setAccessToken($accessToken);

        $this->refreshTokenIfExpired();

        return new Google_Service_Calendar($this->client);
    }


    private function refreshTokenIfExpired()
    {
        $expired = $this->client->isAccessTokenExpired();

        if ($expired) {
            $refreshToken = $this->client->getRefreshToken();
            if ($refreshToken) {
                $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            }
        }
    }

    public function getGooglePermissionUrl($redirectUri = null)
    {
        if ($redirectUri) {
            $this->client->setRedirectUri($redirectUri);
        }
        return $this->client->createAuthUrl();
    }

    public function getAccessTokenFromAuthorizationCode($code)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            logs()->error('Access token error: ', $accessToken);
        }

        return $accessToken;
    }
}
