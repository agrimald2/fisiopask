<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatApiService
{

    private $token;
    private $instanceId;

    public function __construct()
    {
        $this->token = env('CHATAPI_TOKEN');
        $this->instanceId = env('CHATAPI_INSTANCE_ID');
    }


    public function send($phone, $message)
    {
        if ($this->token == null || $this->instanceId == null) {
            return logs()->error('CHATAPI_TOKEN or CHATAPI_INSTANCE_ID not defined on .env file');
        }

        $data = [
            'phone' => $phone,
            'body' => $message,
        ];

        $result = null;

        if (env('CHATAPI_PRODUCTION', false)) {
            $result = $this->makeRequest($data);
        }

        logs()->debug("CHATAPI: {$phone} -> \n" . $message);
        logs()->debug($result);

        return $result;
    }


    private function makeRequest($data)
    {
        // URL for request POST /message
        $token = $this->token;
        $instanceId = $this->instanceId;

        $url = 'https://api.chat-api.com/instance' . $instanceId . '/message?token=' . $token;

        $request = Http::post($url, $data);

        return $request->json();
    }
}
