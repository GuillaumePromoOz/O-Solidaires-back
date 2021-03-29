<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/back/user", name="back_user")
     */
    public function index(): Response
    {
        return $this->render('back/user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
