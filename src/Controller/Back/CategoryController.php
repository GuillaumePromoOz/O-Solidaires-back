<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\PropositionRepository;
use App\Repository\RequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Get all categories
     * 
     * @Route("/back/category/browse", name="back_category_browse", methods={"GET"})
     */
    public function browse(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('back/category/browse.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Get one category
     * 
     * @Route("/back/category/read/{id<\d+>}", name="back_category_read", methods="GET")
     */
    public function read(Category $category = null, PropositionRepository $propositionRepository, RequestRepository $requestRepository): Response
    {
        if ($category === null) {
            throw $this->createNotFoundException('Département non trouvé.');
        }
        $id = $category->getId();

        $propositions = $propositionRepository->findAllByCategory($id);
        $requests = $requestRepository->findAllByCategory($id);

        return $this->render('back/category/read.html.twig',[
            'category' => $category,
            'propositions' => $propositions,
            'requests' => $requests,
            
        ]);
    }
}
