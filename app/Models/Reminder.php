<?php

namespace App\Models;

use App\Models\buttons\Menu;

class Reminder
{
    private $token;
    private $limit = 200;

    public function send(): array
    {
        $bots = Bot::all('id', 'token', 'messenger');
        $result = [];
        foreach ($bots as $bot) {
            $this->token = $bot->token;
            $count = ceil((new BotUsers)
                    ->where('bots_id', $bot->id)
                    ->where('getbitcoin', '<=', '(NOW() - INTERVAL 1 DAY)')
                    ->count() / $this->limit);
            $offset = 0;
            for ($i = 0; $i < $count; $i++) {
                $users = (new BotUsers)->where('bots_id', $bot->id)
                    ->where('getbitcoin', '<=', '(NOW() - INTERVAL 1 DAY)')
                    ->offset($offset)
                    ->limit($this->limit)
                    ->get();
                $result[] = $this->multiSend($users, $bot->messenger);
                $offset += 200;
            }
        }
        return $result;
    }

    private function multiSend($users, string $messenger): array
    {
        foreach ($users as $user) {
            if ($messenger == 'telegram') {
                $data[] = [
                    'key' => $user->chat,
                    'messenger' => $user->messenger,
                    'url' => "https://api.telegram.org/bot" . $this->token . "/sendMessage",
                    'params' => [
                        'text' => Text::valueSubstitution($user, '{reminder}', 'pages', [
                            'satoshi' => (defined('SATOSHI_GET_BITCOIN') ? SATOSHI_GET_BITCOIN : 0)
                        ]),
                        'chat_id' => $user->chat,
                        'parse_mode' => 'HTML',
                        'disable_web_page_preview' => true,
                        'reply_markup' => [
                            'keyboard' => Text::valueSubstitutionArray($user,
                                Menu::main(['messenger' => 'Telegram'])),
                            'resize_keyboard' => true,
                            'one_time_keyboard' => false,
                            'parse_mode' => 'HTML',
                            'selective' => true
                        ]
                    ]
                ];
            } elseif ($messenger == "viber") {
                $data[] = [
                    'key' => $user->chat,
                    'messenger' => $user->messenger,
                    'url' => "https://chatapi.viber.com/pa/send_message",
                    'params' => [
                        'receiver' => $user->chat,
                        'min_api_version' => 7,
                        'type' => 'text',
                        'text' => Text::valueSubstitution($user, '{reminder}', 'pages', [
                            'satoshi' => (defined('SATOSHI_GET_BITCOIN') ? SATOSHI_GET_BITCOIN : 0)
                        ]),
                        'keyboard' => [
                            'Type' => 'keyboard',
                            'InputFieldState' => 'hidden',
                            'DefaultHeight' => 'false',
                            'Buttons' => Text::valueSubstitutionArray($user, Menu::main(['messenger' => 'Viber']))
                        ]
                    ]
                ];
            }
        }
        $res = $this->multiCurl($data ?? []);
        sleep((defined('SLEEP_MAILING') ? SLEEP_MAILING : 2));
        return $res;
    }

    private function multiCurl(array $data): array
    {
        $mh = curl_multi_init();
        $connectionArray = [];

        foreach ($data as $item) {
            $key = $item['key'];
            $data_string = json_encode($item['params']);

            $ch = curl_init($item['url']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($data_string),
            ];

            if ($item['messenger'] == "Viber") {
                $headers[] = 'X-Viber-Auth-Token: ' . $this->token;
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_multi_add_handle($mh, $ch);
            $connectionArray[$key] = $ch;
        }

        $running = null;

        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $responseEmpty = [];
        $content = [];
        $httpCode = [];
        $url = [];

        foreach ($connectionArray as $key => $ch) {
            $content[$key] = curl_multi_getcontent($ch);

            if (empty(curl_multi_getcontent($ch))) {
                $responseEmpty[] = $key;
            }

            $getInfo = curl_getinfo($ch);
            $httpCode[$key] = $getInfo['http_code'];
            $url[$key] = $getInfo['url'];
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);

        $result = [
            "status" => !empty($content) ? "success" : "error",
            "httpCode" => $httpCode,
            "url" => $url,
            "response" => $content
        ];

        if (!empty($responseEmpty)) {
            $result['responseEmpty'] = $responseEmpty;
        }

        return $result;
    }
}
