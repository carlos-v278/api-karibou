<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SocketController extends AbstractController
{
    #[Route('/socket', name: 'app_socket')]
    public function index(): Response
    {
        return $this->render('socket/index.html.twig', [
            'controller_name' => 'SocketController',
        ]);
    }
}
