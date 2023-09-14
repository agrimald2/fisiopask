<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatApiService
{

    private $token;
    private $instanceId;

    public function __construct()
    {
        // Token and Instance
        $this->token = env('CHATAPI_TOKEN');
        $this->instanceId = env('CHATAPI_INSTANCE_ID');
        $this->namespace = env('CHATAPI_NAMESPACE');
    }

    // FISIO KEYS

    //$this->namespace = '85489fc6_2bdc_4137_a3e6_409829280fb5';
    //$this->token = 'rway84qnx1zpgzkg';
    //$this->instanceId = '271955';


    // TEMPLATES

    public function send($phone, $appointment_info, $waba_type)
    {
        //TOKEN VALIDATION
        if ($this->token == null || $this->instanceId == null) {
            return logs()->error('CHATAPI_TOKEN or CHATAPI_INSTANCE_ID not defined on .env file');
        }

        $app = 'appointment_';

        switch ($waba_type) {
            case "confirmation3":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => 'confirmation3 ',
                    'params' => [
                        [
                            'type' => "body",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['patientName']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['startTime']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['doctorName']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_indications']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_address']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_reference']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_maps_link']
                                ],
                            ]

                        ],
                    ]
                ];
                break;
            case "reminder":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => $app . $waba_type,
                    'params' => [
                        [
                            'type' => "body",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['startTime']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_indications']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_address']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_reference']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_maps_link']
                                ],
                            ]

                        ],
                        [
                            'type' => "button",
                            'sub_type' => "url",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['dashboardLink']
                                ]
                            ]
                        ],

                    ]
                ];
                break;
            case "reeschedule":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => $app . $waba_type,
                    'params' => [
                        [
                            'type' => "body",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['patientName']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['startTime']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['doctorName']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_indications']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_address']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_reference']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['office_maps_link']
                                ],
                            ]

                        ],
                        [
                            'type' => "button",
                            'sub_type' => "url",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['dashboardLink']
                                ]
                            ]
                        ],

                    ]
                ];
                break;
            case "cancel":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => $app . $waba_type
                ];
                break;
            case "not_assisted":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => $app . $waba_type,
                    'params' => [
                        [
                            'type' => "body",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['date']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['startTime']
                                ]
                            ]
                        ],
                    ]
                ];
                break;
            case "survey2":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => $app . $waba_type,
                    'params' => [
                        [
                            'type' => "body",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['patientName']
                                ]
                            ]
                        ],
                        [
                            'type' => "button",
                            'sub_type' => "url",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['surveyLink']
                                ]
                            ]
                        ],
                    ]
                ];
                break;
            case "bill_notification":
                $data = [
                    'phone' => $phone,
                    'namespace' => '85489fc6_2bdc_4137_a3e6_409829280fb5',
                    'language' => [
                        'code' => 'es',
                        'policy' => 'deterministic'
                    ],
                    'template' => $waba_type,
                    'params' => [
                        [
                            'type' => "body",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_level']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_amount']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_receiver']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_family']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_subfamily']
                                ],
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_description']
                                ],
                            ]

                        ],
                        [
                            'type' => "button",
                            'sub_type' => "url",
                            'parameters' => [
                                [
                                    "type" => "text",
                                    "text" => $appointment_info['m_id']
                                ],
                            ]
                        ],

                    ]
                ];
                break;
            default:
                $data = [];
        }


        $result = null;

        if (env('CHATAPI_PRODUCTION', false)) {
            $result = $this->makeRequest($data);
        }

        return $result;
    }


    private function makeRequest($data)
    {

        $token = $this->token;
        $instanceId = $this->instanceId;

        $url = 'https://api.1msg.io/' . $instanceId . '/sendTemplate?token=' . $token;

        $request = Http::post($url, $data);

        return $request->json();
    }
}
