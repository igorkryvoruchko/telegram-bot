<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BotController.php',
        ]);
    }

    /**
     * @Route("/bot", name="bot")
     */
    public function bot(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new bot controller!',
            'path' => 'src/Controller/BotController.php',
        ]);
    }
}