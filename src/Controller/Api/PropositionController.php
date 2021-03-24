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

    /**
     * @Route("/propositions", name="api_propositions_create", methods="POST")
     */
    public function createProposition(Request $request, EntityManagerInterface $entityManager,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();
        $proposition = $serializer->deserialize($jsonContent, Proposition::class, 'json');
        $errors = $validator->validate($proposition);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $entityManager->persist($proposition);
        $entityManager->flush();
        return $this->json('Proposition créée', Response::HTTP_CREATED);
    }

    /**
     * Edit Proposition(PATCH)
     * 
     * @Route("/propositions/{id<\d+>}", name="api_propositions_patch", methods={"PATCH"})
     */
    public function patch(Proposition $proposition = null, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        // 1. On souhaite modifier le film dont l'id est transmis via l'URL

        // 404 ?
        if ($proposition === null) {
            // On retourne un message JSON + un statut 404
            return $this->json(['error' => 'Proposition non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        // Notre JSON qui se trouve dans le body
        $jsonContent = $request->getContent();


        // @todo Pour PATCH, s'assurer qu'on au moins un champ

        $serializer->deserialize(
            $jsonContent,
            Proposition::class,
            'json',
            // On a cet argument en plus qui indique au serializer quelle entité existante modifier
            [AbstractNormalizer::OBJECT_TO_POPULATE => $proposition]
        );

        // Validation de l'entité désérialisée
        $errors = $validator->validate($proposition);
        // Génération des erreurs
        if (count($errors) > 0) {
            // On retourne le tableau d'erreurs en Json au front avec un status code 422
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $proposition->setUpdatedAt(new \DateTime());
        // On flush $movie qui a été modifiée par le Serializer
        $em->flush();

        // @todo Conditionner le message de retour au cas où
        // l'entité ne serait pas modifiée
        return $this->json(['message' => 'Proposition modifiée.'], Response::HTTP_OK);
    }
}
