<?php

namespace App\Controller\Api;

use App\Entity\Request as RequestEntity;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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

    /**
     * @Route("/requests", name="api_requests_create", methods="POST")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();
        $userRequest = $serializer->deserialize($jsonContent, RequestEntity::class, 'json');
        $errors = $validator->validate($userRequest);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $role = $userRequest->getUser()->getRoles();
        $roleIndex= $role[0];
        //dd($roleIndex);
        if ($roleIndex !== "ROLE_BENEFICIARY"){
            return $this->json(['error' => 'Cet utilisateur n\'est pas un bénéficiaire'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->persist($userRequest);
        $entityManager->flush();
        return $this->json('Demande créée', Response::HTTP_CREATED);
    }

    /**
     * Edit Request(PATCH)
     * 
     * @Route("/requests/{id<\d+>}", name="api_requests_patch", methods={"PATCH"})
     */
    public function patch(RequestEntity $userRequest = null, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator, Request $request)
    {
        // 1. On souhaite modifier le film dont l'id est transmis via l'URL

        // 404 ?
        if ($userRequest === null) {
            // On retourne un message JSON + un statut 404
            return $this->json(['error' => 'Demande non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        // Notre JSON qui se trouve dans le body
        $jsonContent = $request->getContent();


        // @todo Pour PATCH, s'assurer qu'on au moins un champ

        $serializer->deserialize(
            $jsonContent,
            RequestEntity::class,
            'json',
            // On a cet argument en plus qui indique au serializer quelle entité existante modifier
            [AbstractNormalizer::OBJECT_TO_POPULATE => $userRequest]
        );

        // Validation de l'entité désérialisée
        $errors = $validator->validate($userRequest);
        // Génération des erreurs
        if (count($errors) > 0) {
            // On retourne le tableau d'erreurs en Json au front avec un status code 422
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $role = $userRequest->getUser()->getRoles();
        $roleIndex = $role[0];
        //dd($roleIndex);
        if ($roleIndex !== "ROLE_BENEFICIARY"){
            return $this->json(['error' => 'Cet utilisateur n\'est pas un bénéficiaire'], Response::HTTP_NOT_FOUND);
        }

        $userRequest->setUpdatedAt(new \DateTime());
        // On flush $movie qui a été modifiée par le Serializer
        $em->flush();

        // @todo Conditionner le message de retour au cas où
        // l'entité ne serait pas modifiée
        return $this->json(['message' => 'Demande modifiée.'], Response::HTTP_OK);
    }
}
