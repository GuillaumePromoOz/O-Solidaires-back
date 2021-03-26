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

        /// Checks if category exist if not returns a 404
        if ($category === null) {

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
     * Add category
     * 
     * @Route("/categories", name="api_categories_create", methods="POST")
     */
    public function createCategory(Request $request, EntityManagerInterface $entityManager,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        // We fetch the data sent via method POST into our Symfony Component "Request"
        // the content is in the body of the request
        // the content is stored into variable $jsonContent
        $jsonContent = $request->getContent();

        // We deserialize the JSON content into the User Entity
        // Basically it transforms the json into an object
        $category = $serializer->deserialize($jsonContent, Category::class, 'json');

        //Generates errors
        $errors = $validator->validate($category);
        //returns errors
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // save !
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
        // We would like to modify the request via the id sent by the URL

        // Checks for errors
        if ($category === null) {
            return $this->json(['error' => 'Categories non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            Category::class,
            'json',
            // This extra argument specifies to the serializer which existing entity to modify
            [AbstractNormalizer::OBJECT_TO_POPULATE => $category]
        );

        // Validation of the deserialized entity
        $errors = $validator->validate($category);
        // Checks for errors
        if (count($errors) > 0) {
            // Returns errors
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category->setUpdatedAt(new \DateTime());

        // save !
        $em->flush();

        return $this->json(['message' => 'Catégorie modifiée.'], Response::HTTP_OK);
    }
}
