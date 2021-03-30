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
        $propositions = $propositionRepository->findAll();

        return $this->render('back/proposition/browse.html.twig', [
            'propositions' => $propositions,
        ]);
    }

    /**
     * Get one proposition
     * 
     * @Route("/back/proposition/read/{id<\d+>}", name="back_proposition_read", methods="GET")
     */
    public function read(Proposition $proposition = null, UserRepository $userRepository): Response
    {
        if ($proposition === null) {
            throw $this->createNotFoundException('Département non trouvé.');
        }
        $id = $proposition->getId();
        


        return $this->render('back/proposition/read.html.twig',[
            'proposition' => $proposition,
            
        ]);
    }

     /**
     * Create proposition
     *
     * @Route("/back/proposition/add", name="back_proposition_add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager ): Response
    {
       
        $proposition = new Proposition();

        $form = $this->createForm(PropositionType::class, $proposition);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $entityManager->persist($proposition);
            $entityManager->flush();

            return $this->redirectToRoute('back_proposition_browse');
        }

        return $this->render('back/proposition/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
