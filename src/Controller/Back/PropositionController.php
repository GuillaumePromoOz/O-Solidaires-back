<?php

namespace App\Controller\Back;

use App\Repository\PropositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropositionController extends AbstractController
{
    /**
     * Get all propositions
     * 
     * @Route("/back/proposition/browse", name="back_proposition_browse", methods={"GET"})
     */
    public function browse(PropositionRepository $propositionRepository): Response
    {
        $propositions = $propositionRepository->findAll();

        return $this->render('back/proposition/browse.html.twig', [
            'propositions' => $propositions,
        ]);
    }
}
