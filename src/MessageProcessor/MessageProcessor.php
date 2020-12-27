<?php


namespace App\MessageProcessor;


use Borsaco\TelegramBotApiBundle\Service\Bot;

class MessageProcessor
{
    protected $bot;

    /**
     * MessageProcessor constructor.
     * @param $bot
     */
    public function __construct(Bot $bot)
    {
        $this->bot = $bot->getBot('first');
    }


    public function handle($response)
    {
        if(!empty($response) && !empty($response->message)){
            $userId = $response->message->from->id;
            $messageText = mb_strtolower($response->message->text);
            switch ($messageText){
                case "/start":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Привет']);
                    break;
                case "/help":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Чем помочь?']);
                    break;
                case "и че":
                case "и чё":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Через плечо']);
                    break;
                case "спасибо":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'от души братуха!!! от души']);
                    break;
                case "танцевать":
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'https://www.youtube.com/watch?v=w9okGAKOyYk']);
                    break;
                default:
                    $this->bot->sendMessage(['chat_id' => $userId, 'text' => 'Я не понимаю о чем вы!']);
                    break;
            }


        }
    }
}