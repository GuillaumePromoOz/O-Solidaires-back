<?php

namespace App\Controller\Back;

use App\Entity\Proposition;
use App\Form\PropositionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PropositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropositionController extends AbstractController
{
    /**
     * Get all propositions
     * 
     * @Route("/back/proposition/browse", name="back_proposition_browse", methods={"GET"})
     */
    public function browse(PropositionRepository $propositionRepository): Response
    {
        $propositions = $propositionRepository->findAllOrderedByIdDesc();

        return $this->render('back/proposition/browse.html.twig', [
            'propositions' => $propositions,
        ]);
    }

    /**
     * Get one proposition
     * 
     * @Route("/back/proposition/read/{id<\d+>}", name="back_proposition_read", methods="GET")
     */
    public function read(Proposition $proposition = null): Response
    {
        if ($proposition === null) {
            throw $this->createNotFoundException('Proposition non trouvée.');
        }

        return $this->render('back/proposition/read.html.twig', [
            'proposition' => $proposition,

        ]);
    }

    /**
     * Create proposition
     *
     * @Route("/back/proposition/add", name="back_proposition_add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $proposition = new Proposition();

        $form = $this->createForm(PropositionType::class, $proposition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($proposition);
            $entityManager->flush();

            // Flash
            $this->addFlash('success', 'Proposition créée avec succès !');


            return $this->redirectToRoute('back_proposition_browse');
        }

        return $this->render('back/proposition/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit one proposition
     * 
     * @Route("/back/proposition/edit/{id}", name="back_proposition_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Proposition $proposition): Response
    {


        $form = $this->createForm(PropositionType::class, $proposition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $proposition->setUpdatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            // Flash
            $this->addFlash('warning', 'Proposition modifiée !');

            return $this->redirectToRoute('back_proposition_browse');
        }

        return $this->render('back/proposition/edit.html.twig', [
            'proposition' => $proposition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * DELETE one Proposition
     * 
     * @Route("/back/proposition/delete/{id<\d+>}", name="back_proposition_delete", methods={"DELETE"})
     */
    public function delete(Proposition $proposition = null, Request $request, EntityManagerInterface $entityManager)
    {

        if ($proposition === null) {
            throw $this->createNotFoundException('Proposition non trouvée.');
        }

        $submittedToken = $request->request->get('token');


        if (!$this->isCsrfTokenValid('delete', $submittedToken)) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $entityManager->remove($proposition);
        $entityManager->flush();

        // Flash
        $this->addFlash('danger', 'propostion supprimée !');

        return $this->redirectToRoute('back_proposition_browse');
    }
}
