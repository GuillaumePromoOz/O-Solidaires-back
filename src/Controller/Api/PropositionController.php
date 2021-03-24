<?php

namespace App\Controller\Api;

use App\Entity\Proposition;
use App\Repository\PropositionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropositionController extends AbstractController
{
    /**
     * Get all Propositions
     * 
     * @Route("/propositions", name="api_propositions_read", methods="GET")
     */
    public function read(PropositionRepository $propositionRepository): Response
    {
        $propostions = $propositionRepository->findAll();

        return $this->json($propostions, 200, [], ['groups' => 'propositions_read']);
    }

    /**
     * Get one proposition
     * 
     * @Route("/propositions/{id<\d+>}", name="api_propositions_read_item", methods="GET")
     */
    public function readItem(Proposition $proposition = null): Response
    {

        /// la 404
        if ($proposition === null) {

            // Optionnel, message pour le front
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Propostion non trouvée.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($proposition, 200, [], ['groups' => [
            'propositions_read',
        ]]);
    }
}
