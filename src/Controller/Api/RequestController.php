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
        $requests = $requestRepository->findAllOrderedByIdDesc();

        return $this->json($requests, 200, [], ['groups' => 'requests_read']);
    }

    /**
     * Get one request
     * 
     * @Route("/requests/{id<\d+>}", name="api_requests_read_item", methods="GET")
     */
    public function readItem(RequestEntity $request = null): Response
    {

        // Checks if request is null
        // if request is null then it returns a 404
        if ($request === null) {

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
     * Add request
     * 
     * @Route("/requests", name="api_requests_create", methods="POST")
     */
    public function createRequest(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        // We fetch the data sent via method POST into our Symfony Component "Request"
        // the content is in the body of the request
        // the content is stored into variable $jsonContent
        $jsonContent = $request->getContent();

        // We deserialize the JSON content into the User Entity
        // Basically it transforms the json into an object
        $userRequest = $serializer->deserialize($jsonContent, RequestEntity::class, 'json');
        //Generates errors
        $errors = $validator->validate($userRequest);
        // Returns errors
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // from the Request Entity we fetch the user (getUser) then from the User Entity we fetch the roles (getRoles)
        // we store the date inside variable $role
        $role = $userRequest->getUser()->getRoles();
        // we fetch index 0 from the $role array and store that into the $roleIndex variable ( 0 => "ROLE_BENEFICIARY")
        $roleIndex = $role[0];
        // if roleIndex is different from "ROLE_BENEFICIARY" then return a 404 error
        if ($roleIndex !== "ROLE_BENEFICIARY") {
            return $this->json(['error' => 'Cet utilisateur n\'est pas un bénéficiaire'], Response::HTTP_NOT_FOUND);
        }

        // else, save new request in database !
        $entityManager->persist($userRequest);
        $entityManager->flush();

        return $this->json('Demande créée', Response::HTTP_CREATED, []);
    }

    /**
     * Edit Request(PATCH)
     * 
     * @Route("/requests/{id<\d+>}", name="api_requests_patch", methods={"PATCH"})
     */
    public function patchRequest(RequestEntity $userRequest = null, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator, Request $request)
    {
        // Le User courant a-t-il le droits de modifier cette question
        $this->denyAccessUnlessGranted('patchRequest', $userRequest);

        // We would like to modify the request via the id sent by the URL

        // Checks for errors
        if ($userRequest === null) {
            return $this->json(['error' => 'Demande non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            RequestEntity::class,
            'json',
            // This extra argument specifies to the serializer which existing entity to modify
            [AbstractNormalizer::OBJECT_TO_POPULATE => $userRequest]
        );

        $errors = $validator->validate($userRequest);
        // Generates errors
        if (count($errors) > 0) {
            // Returns errors
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $role = $userRequest->getUser()->getRoles();
        $roleIndex = $role[0];
        if ($roleIndex !== "ROLE_BENEFICIARY") {
            return $this->json(['error' => 'Cet utilisateur n\'est pas un bénéficiaire'], Response::HTTP_NOT_FOUND);
        }

        $userRequest->setUpdatedAt(new \DateTime());

        // save !
        $em->flush();

        return $this->json(['message' => 'Demande modifiée.'], Response::HTTP_OK);
    }
}
