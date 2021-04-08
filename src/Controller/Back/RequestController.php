<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use App\Repository\RequestRepository;
use App\Entity\Request as RequestEntity;
use App\Form\RequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RequestController extends AbstractController
{
    /**
     * Get all requests
     * 
     * @Route("/back/request/browse", name="back_request_browse", methods={"GET"})
     */
    public function browse(RequestRepository $requestRepository): Response
    {
        $requests = $requestRepository->findAllOrderedByIdDesc();

        return $this->render('back/request/browse.html.twig', [
            'requests' => $requests,
        ]);
    }

    /**
     * Get one request
     * 
     * @Route("/back/request/read/{id<\d+>}", name="back_request_read", methods="GET")
     */
    public function read(RequestEntity $requestModel = null): Response
    {
        if ($requestModel === null) {
            throw $this->createNotFoundException('Demande non trouvée.');
        }

        return $this->render('back/request/read.html.twig', [
            'request' => $requestModel,

        ]);
    }

    /**
     * Create request
     *
     * @Route("/back/request/add", name="back_request_add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

        $requestModel = new RequestEntity();

        $form = $this->createForm(RequestType::class, $requestModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($requestModel);
            $entityManager->flush();

            // Flash
            $this->addFlash('success', 'Demande créée avec succès !');


            return $this->redirectToRoute('back_request_browse');
        }

        return $this->render('back/request/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit one request
     * 
     * @Route("/back/request/edit/{id}", name="back_request_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RequestEntity $requestModel): Response
    {


        $form = $this->createForm(RequestType::class, $requestModel);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $requestModel->setUpdatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            // Flash
            $this->addFlash('warning', 'Demande modifiée !');

            return $this->redirectToRoute('back_request_browse');
        }

        return $this->render('back/request/edit.html.twig', [
            'request' => $requestModel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * DELETE one request
     * 
     * @Route("/back/request/delete/{id<\d+>}", name="back_request_delete", methods={"DELETE"})
     */
    public function delete(RequestEntity $requestModel = null, Request $request, EntityManagerInterface $entityManager)
    {

        if ($requestModel === null) {
            throw $this->createNotFoundException('Demande non trouvée.');
        }

        $submittedToken = $request->request->get('token');


        if (!$this->isCsrfTokenValid('delete', $submittedToken)) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $entityManager->remove($requestModel);
        $entityManager->flush();

        // Flash
        $this->addFlash('danger', 'Demande supprimée !');

        return $this->redirectToRoute('back_request_browse');
    }
}
