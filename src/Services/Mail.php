<?php

namespace App\Services;

/*
This call sends a message based on a template.
 */
require 'vendor/autoload.php';
use Mailjet\Client;
use \Mailjet\Resources;

class Mail
{
    private $api_key="f7e7b3503315ef55180be9a384d7171a";
    private $api_key_private="60f667a437d5b4e9f65640fb58793735";

    public function send($emailTo, $name, $sujet, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_private, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "pascal.briffard@gmail.com",
                        'Name' => "Studi #ECF 12/2021",
                    ],
                    'To' => [
                        [
                            'Email' => $emailTo,
                            'Name' => $name,
                        ],
                    ],
                    'TemplateID' => 3277308,
                    'TemplateLanguage' => true,
                    'Subject' => $sujet,
                    'Variables' => [
                        'content' => $content,
                    ]
                ],
            ],
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && var_dump($response->getData());
    }


}
