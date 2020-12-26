<?php

namespace App\Controller;

use App\MessageProcessor\MessageProcessor;
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
    protected $messageProcessor;

    /**
     * BotController constructor.
     * @param $messageProcessor
     */
    public function __construct(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }

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
        $this->messageProcessor->handle($response);

        return new JsonResponse(['ok']);
    }


}
