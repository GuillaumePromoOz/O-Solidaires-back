<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
}
