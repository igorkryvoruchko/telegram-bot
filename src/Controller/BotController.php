<?php

namespace App\Controller;

use Illuminate\Support\Facades\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;
use Borsaco\TelegramBotApiBundle\Service\Bot;


class BotController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"}, name="bot")
     * @param Bot $bot
     * @param Request $request
     * @return JsonResponse
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function telegram(Bot $bot, Request $request): JsonResponse
    {
        $response = json_decode((string)$request->getContent());
        $firstBot = $bot->getBot('first');

        if(!empty($response) && !empty($response->message)){
            $userId = $response->message->from->id;
            $messageText = mb_strtolower($response->message->text);
            switch ($messageText){
                case "/start":
                    $firstBot->sendMessage(['chat_id' => $userId, 'text' => 'Привет']);
                    break;
                case "/help":
                    $firstBot->sendMessage(['chat_id' => $userId, 'text' => 'Чем помочь?']);
                    break;
            }


        }
        return new JsonResponse(['ok']);
    }


}
