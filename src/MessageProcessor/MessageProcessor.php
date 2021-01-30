<?php


namespace App\MessageProcessor;


use Borsaco\TelegramBotApiBundle\Service\Bot;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Telegram\Bot\Keyboard\Keyboard;

class MessageProcessor
{
    protected $bot;

    private $client;

    protected $keyboard;

    /**
     * MessageProcessor constructor.
     * @param Bot $bot
     * @param HttpClientInterface $client
     */
    public function __construct(Bot $bot, HttpClientInterface $client)
    {
        $this->bot = $bot->getBot('first');

        $this->client = $client;

        $this->keyboard = new Keyboard();
    }

    protected function keyboard()
    {
        return new Keyboard();
    }

    protected function replayKeyboard()
    {
        return $this->keyboard->make(['resize_keyboard' => true])
            ->row(
                $this->keyboard->Button(['text' => '/кто красавчик?']),
                $this->keyboard->Button(['text' => '/пакет'])
            )
            ->row(
                $this->keyboard->Button(['text' => '/танцевать']),
                $this->keyboard->Button(['text' => '/курс валют']),
                $this->keyboard->Button(['text' => '/оплатить'])
            )
        ;
    }


    public function handle($response)
    {
        if(!empty($response) && !empty($response->message)){
            $userId = $response->message->from->id;
            $messageText = mb_strtolower($response->message->text);
            switch ($messageText){
                case "/start":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Привет', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "/help":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Чем помочь?', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "и че":
                case "и чё":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Через плечо', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "спасибо":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'от души братуха!!! от души', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "/танцевать":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'https://www.youtube.com/watch?v=w9okGAKOyYk', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "/оплатить":
                    $inline_keyboard = $this->keyboard->make()->inline()
                        ->row(
                            $this->keyboard->inlineButton(['text' => 'Наличные', 'callback_data' => 'cash']),
                            $this->keyboard->inlineButton(['text' => 'Безналичные', 'callback_data' => 'cashless'])
                        );
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Как хотите оплатить?', 'reply_markup' => $inline_keyboard]);
                    break;
                case "/пакет":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'https://github.com/borsaco/TelegramBotApiBundle', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "/курс валют":
                    foreach ($this->getRates() as $rate){
                        $this->bot->sendMessage(['chat_id' => $userId, 'text' => $rate['ccy'].' покупка: '.$rate['buy']. $rate['base_ccy'] . ' продажа: '.$rate['sale']. $rate['base_ccy'] ]);
                    }
                    break;
                case "cash":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Принято, наличка', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                case "/кто красавчик?":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Конечно Игорь Криворучко!', 'reply_markup' => $this->replayKeyboard()]);
                    break;
                default:
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Я не понимаю о чем вы!', 'reply_markup' => $this->replayKeyboard()]);
                    break;
            }


        }else{
            $this->callBackQueryHandle($response);
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

    protected function callBackQueryHandle($response)
    {
        if(!empty($response) && !empty($response->callback_query)){
            $userId = $response->callback_query->from->id;
            $data = mb_strtolower($response->callback_query->data);
            $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Готово - '. $data, 'reply_markup' => $this->replayKeyboard()]);
        }
    }
}