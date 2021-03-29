<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Admins list
     * 
     * @Route("/back/admin/browse", name="back_admin_browse", methods={"GET"})
     */
    public function adminBrowse(UserRepository $userRepository): Response
    {
        $role = '["ROLE_ADMIN"]';
        $admins = $userRepository->findAllByRole($role);
        //dd($admins);
        return $this->render('back/user/browse.html.twig', [
            'admins' => $admins,
        ]);
    }

    /**
     * Shows one admin
     * 
     * @Route("/back/admin/read/{id}", name="admin_read")
     */
    public function adminRead(UserRepository $userRepository, User $user)
    {

        return $this->render('back/user/read.html.twig', [
            'admin' => $user,
        ]);
    }

     /**
     * Form to add an admin
     * 
     * @Route("/back/admin/add", name="admin_add")
     */
    public function adminAdd(Request $request, EntityManagerInterface $entityManager)
    {
        // the entity to create
        $admin = new User();

        // generates form
        $form = $this->createForm(UserType::class, $admin);

        // we inspect the request and map the datas posted on the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // save the new admin
            $entityManager->persist($admin);
            $entityManager->flush();

            // On redirige vers la liste 
            return $this->redirectToRoute('back_admin_browse');
        }

        return $this->render('back/user/admin_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
