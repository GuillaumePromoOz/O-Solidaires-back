<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    /**
     * @Route("/back/request", name="back_request")
     */
    public function index(): Response
    {
        return $this->render('back/request/index.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }
}
