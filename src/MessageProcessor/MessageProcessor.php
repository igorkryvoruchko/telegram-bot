<?php


namespace App\MessageProcessor;


use Borsaco\TelegramBotApiBundle\Service\Bot;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Telegram\Bot\Keyboard\Keyboard;

class MessageProcessor
{
    protected $bot;

    private $client;

    /**
     * MessageProcessor constructor.
     * @param Bot $bot
     * @param HttpClientInterface $client
     */
    public function __construct(Bot $bot, HttpClientInterface $client)
    {
        $this->bot = $bot->getBot('first');

        $this->client = $client;
    }


    public function handle($response)
    {
        if(!empty($response) && !empty($response->message)){
            $keyboard = new Keyboard();
            $replay_keyboard = $keyboard->make(['resize_keyboard' => true])
                ->row(
                    $keyboard->Button(['text' => '/кто красавчик?']),
                    $keyboard->Button(['text' => '/пакет'])
                )
                ->row(
                    $keyboard->Button(['text' => '/танцевать']),
                    $keyboard->Button(['text' => '/курс валют'])
                )
            ;
            $userId = $response->message->from->id;
            $messageText = mb_strtolower($response->message->text);
            switch ($messageText){
                case "/start":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Привет', 'reply_markup' => $replay_keyboard]);
                    break;
                case "/help":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Чем помочь?', 'reply_markup' => $replay_keyboard]);
                    break;
                case "и че":
                case "и чё":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Через плечо', 'reply_markup' => $replay_keyboard]);
                    break;
                case "спасибо":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'от души братуха!!! от души', 'reply_markup' => $replay_keyboard]);
                    break;
                case "/танцевать":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'https://www.youtube.com/watch?v=w9okGAKOyYk', 'reply_markup' => $replay_keyboard]);
                    break;
                case "/курс валют":
                    foreach ($this->getRates() as $rate){
                        $this->bot->sendMessage(['chat_id' => $userId, 'text' => $rate['ccy'].' покупка: '.$rate['buy']. $rate['base_ccy'] . ' продажа: '.$rate['sale']. $rate['base_ccy'] ]);
                    }
                    break;
                case "/пакет":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'https://github.com/borsaco/TelegramBotApiBundle', 'reply_markup' => $replay_keyboard]);
                    break;
                case "/кто красавчик?":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Конечно Игорь Криворучко!', 'reply_markup' => $replay_keyboard]);
                    break;
                default:
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Я не понимаю о чем вы!', 'reply_markup' => $replay_keyboard]);
                    break;
            }


        }
    }

    protected function getRates()
    {
        $response = $this->client->request(
            'GET',
            'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5'
        );

        return $response->toArray();
    }
}