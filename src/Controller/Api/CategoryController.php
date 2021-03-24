<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
     /**
     * Get all categories
     * 
     * @Route("/categories", name="api_categories_read", methods="GET")
     */
    public function read(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->json($categories, 200, [], ['groups' => 'categories_read']);
    }

    /**
     * Get one category
     * 
     * @Route("/categories/{id<\d+>}", name="api_categories_read_item", methods="GET")
     */
    public function readItem(Category $category = null): Response
    {

        /// la 404
        if ($category === null) {

            // Optionnel, message pour le front
            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Catégorie non trouvée.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, 200, [], ['groups' => [
            'categories_read',
        ]]);
    }

     /**
     * @Route("/categories", name="api_categories_create", methods="POST")
     */
    public function createCategory(Request $request, EntityManagerInterface $entityManager,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();
        $category = $serializer->deserialize($jsonContent, Category::class, 'json');
        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $entityManager->persist($category);
        $entityManager->flush();
        return $this->json('Catégorie créée', Response::HTTP_CREATED);
    }

    /**
     * Edit Category(PATCH)
     * 
     * @Route("/categories/{id<\d+>}", name="api_categories_patch", methods={"PATCH"})
     */
    public function patch(Category $category = null, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        // 1. On souhaite modifier le film dont l'id est transmis via l'URL

        // 404 ?
        if ($category === null) {
            // On retourne un message JSON + un statut 404
            return $this->json(['error' => 'Categories non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        // Notre JSON qui se trouve dans le body
        $jsonContent = $request->getContent();

        
        // @todo Pour PATCH, s'assurer qu'on au moins un champ
        
        $serializer->deserialize(
            $jsonContent,
            Category::class,
            'json',
            // On a cet argument en plus qui indique au serializer quelle entité existante modifier
            [AbstractNormalizer::OBJECT_TO_POPULATE => $category]
        );

        // Validation de l'entité désérialisée
        $errors = $validator->validate($category);
        // Génération des erreurs
        if (count($errors) > 0) {
            // On retourne le tableau d'erreurs en Json au front avec un status code 422
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $category->setUpdatedAt(new \DateTime());
        // On flush $movie qui a été modifiée par le Serializer
        $em->flush();

        // @todo Conditionner le message de retour au cas où
        // l'entité ne serait pas modifiée
        return $this->json(['message' => 'Catégorie modifiée.'], Response::HTTP_OK);
    }

}
