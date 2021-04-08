<?php

namespace App\Controller\Api;

use App\Entity\Proposition;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PropositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
        $propostions = $propositionRepository->findAllOrderedByIdDesc();

        return $this->json($propostions, 200, [], ['groups' => 'propositions_read']);
    }

    /**
     * Get one proposition
     * 
     * @Route("/propositions/{id<\d+>}", name="api_propositions_read_item", methods="GET")
     */
    public function readItem(Proposition $proposition = null): Response
    {

        // Checks if request is null
        // if request is null then it returns a 404
        if ($proposition === null) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Propostion non trouvée.',
            ];
            // We define a custom message and generate a HTTP 404 status code
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($proposition, 200, [], ['groups' => [
            'propositions_read',
        ]]);
    }

    /**
     * Add proposition
     * 
     * @Route("/propositions", name="api_propositions_create", methods="POST")
     */
    public function createProposition(Request $request, EntityManagerInterface $entityManager,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        // We fetch the data sent via method POST into our Symfony Component "Request"
        // the content is in the body of the request
        // the content is store into variable $jsonContent
        $jsonContent = $request->getContent();

        // We deserialize the JSON content into the Proposition Entity
        // Basically it transforms the json into an object
        $proposition = $serializer->deserialize($jsonContent, Proposition::class, 'json');

        // The recieved data is checked for anomalies (ex, empty fields, invalid values)
        // The Validator alidates a property of an object against the constraints specified
        // for this property.
        $errors = $validator->validate($proposition);

        // Counts the number of errors, if bigger than zero -> returns error 422
        // returns an array of errors to the frontend
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // from the Proposition Entity we fetch the user (getUser) then from the User Entity we fetch the roles (getRoles)
        // we store the date inside variable $role
        $role = $proposition->getUser()->getRoles();

        // we fetch index 0 from the $role array and store that into the $roleIndex variable ( 0 => "ROLE_VOLUNTEER")
        $roleIndex = $role[0];

        // if roleIndex is different from "ROLE_VOLUNTEER" then return a 404 error
        if ($roleIndex !== "ROLE_VOLUNTEER") {
            return $this->json(['error' => 'Cet utilisateur n\'est pas un bénévole'], Response::HTTP_NOT_FOUND);
        }

        // else, save new proposition in database !
        $entityManager->persist($proposition);
        $entityManager->flush();

        return $this->json('Proposition créée', Response::HTTP_CREATED);
    }

    /**
     * Edit Proposition(PATCH)
     * 
     * @Route("/propositions/{id<\d+>}", name="api_propositions_patch", methods={"PATCH"})
     */
    public function patchProposition(Proposition $proposition = null, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {

        //  $user = $this->getUser();
        //    Si le user n'est pas l'auteur de la question, il sort
        // if ($user !== $proposition->getUser()) {
        //     throw $this->createAccessDeniedException('Pas le droit !');
        // }

        // Le User courant a-t-il le droits de modifier cette question (gèré via le PropositionVoter)
        $this->denyAccessUnlessGranted('patchProposition', $proposition);

        // We would like to modify the request via the id sent by the URL

        if ($proposition === null) {

            return $this->json(['error' => 'Proposition non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            Proposition::class,
            'json',
            // This extra argument specifies to the serializer which existing entity to modify
            [AbstractNormalizer::OBJECT_TO_POPULATE => $proposition]
        );

        $errors = $validator->validate($proposition);
        // Generates errors
        if (count($errors) > 0) {
            // Returns errors
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $role = $proposition->getUser()->getRoles();
        $roleIndex = $role[0];
        if ($roleIndex !== "ROLE_VOLUNTEER") {
            return $this->json(['error' => 'Cet utilisateur n\'est pas un bénévole'], Response::HTTP_NOT_FOUND);
        }

        $proposition->setUpdatedAt(new \DateTime());

        $em->flush();

        return $this->json(['message' => 'Proposition modifiée.'], Response::HTTP_OK);
    }
}
