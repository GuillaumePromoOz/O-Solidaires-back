<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropositionController extends AbstractController
{
    /**
     * @Route("/back/proposition", name="back_proposition")
     */
    public function index(): Response
    {
        return $this->render('back/proposition/index.html.twig', [
            'controller_name' => 'PropositionController',
        ]);
    }
}
