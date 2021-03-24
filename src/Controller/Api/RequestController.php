<?php

namespace App\Controller\Api;

use App\Entity\Request;
use App\Repository\RequestRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestController extends AbstractController
{
    /**
     * Get all requests
     * 
     * @Route("/requests", name="api_requests_read", methods="GET")
     */
    public function read(RequestRepository $requestRepository): Response
    {
        $requests = $requestRepository->findAll();

        return $this->json($requests, 200, [], ['groups' => 'requests_read']);
    }

    /**
     * Get one request
     * 
     * @Route("/requests/{id<\d+>}", name="api_requests_read_item", methods="GET")
     */
    public function readItem(Request $request = null): Response
    {

        /// la 404
        if ($request === null) {

            // Optionnel, message pour le front
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Demande non trouvée.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($request, 200, [], ['groups' => [
            'requests_read',
        ]]);
    }
}
