<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\RequestRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PropositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * Get all categories
     * 
     * @Route("/back/category/browse", name="back_category_browse", methods={"GET", "POST"})
     */
    public function browse(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {

        $category = new Category();


        $form = $this->createForm(CategoryType::class, $category);


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //dd($data);
            $entityManager->persist($category);
            $entityManager->flush();

            // Flash
            $this->addFlash('success', 'Catégorie créée avec succès !');

            return $this->redirectToRoute('back_category_browse');
        }

        $categories = $categoryRepository->findAllOrderedByIdDesc();

        return $this->render('back/category/browse.html.twig', [
            'form' => $form->createView(),
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
            throw $this->createNotFoundException('Categorie non trouvée.');
        }
        $id = $category->getId();

        $propositions = $propositionRepository->findAllByCategory($id);
        $requests = $requestRepository->findAllByCategory($id);

        return $this->render('back/category/read.html.twig', [
            'category' => $category,
            'propositions' => $propositions,
            'requests' => $requests,

        ]);
    }

    /**
     * Edit one category
     * 
     * @Route("/back/category/edit/{id}", name="back_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {


        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setUpdatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            // Flash
            $this->addFlash('warning', 'Categorie modifiée !');

            return $this->redirectToRoute('back_category_browse');
        }

        return $this->render('back/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * DELETE one Category
     * 
     * @Route("/back/category/delete/{id<\d+>}", name="back_category_delete", methods={"DELETE"})
     */
    public function delete(Category $category = null, Request $request, EntityManagerInterface $entityManager)
    {

        if ($category === null) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        $submittedToken = $request->request->get('token');


        if (!$this->isCsrfTokenValid('delete', $submittedToken)) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $entityManager->remove($category);
        $entityManager->flush();

        // Flash
        $this->addFlash('danger', 'Catégorie supprimée !');

        return $this->redirectToRoute('back_category_browse');
    }
}
