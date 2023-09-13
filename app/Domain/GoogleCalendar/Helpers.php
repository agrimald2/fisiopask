<?php

namespace App\Domain\GoogleCalendar;


use Google_Service_Calendar_Event;

trait Helpers
{
    public function auth($accessToken)
    {
        return $this->getCalendarFromAccessToken($accessToken);
    }

    public function event(string $name, $start, $end, $description = '')
    {
        $start = now()->parse($start)->toIso8601String();
        $end = now()->parse($end)->toIso8601String();

        return new Google_Service_Calendar_Event([
            'summary' => $name,
            'start' => ['dateTime' => $start],
            'end' => ['dateTime' => $end],
            'description' => $description
        ]);
    }

    public function insert($accessToken, $name, $start, $end, $description = '')
    {
        $service = calendar()->auth($accessToken);

        $debugEvent = [
            'name' => $name,
            'start' => $start,
            'end' => $end,
            'description' => $description,
        ];

        if (!$service) {
            return null;
        }


        $calendarId = 'primary';
        $event = calendar()->event($name, $start, $end, $description);

        $insertedEvent = $service
            ->events
            ->insert(
                $calendarId,
                $event
            );

        return $insertedEvent;
    }
}
