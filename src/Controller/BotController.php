<?php

namespace App\Controller;

use Illuminate\Support\Facades\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;

class BotController extends AbstractController
{
    /**
     * @Route("/", name="bot")
     */
    public function telegram(Request $request, Logger $logger): JsonResponse {
        $result = json_decode($request->getContent(), true);
        $logger->info($request->getContent());
        return new JsonResponse([]);
    }


}
