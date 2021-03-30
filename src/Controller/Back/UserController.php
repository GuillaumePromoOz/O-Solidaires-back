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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /*          _____  __  __ _____ _   _  
      /\   |  __ \|  \/  |_   _| \ | |/ ____|
     /  \  | |  | | \  / | | | |  \| | (___  
    / /\ \ | |  | | |\/| | | | | . ` |\___ \ 
   / ____ \| |__| | |  | |_| |_| |\  |____) |
  /_/    \_\_____/|_|  |_|_____|_| \_|_____/ 
*/

    /**
     * Admins list
     * 
     * @Route("/back/admin/browse", name="back_admin_browse", methods={"GET"})
     */
    public function adminBrowse(UserRepository $userRepository): Response
    {
        // We fetch the user by its role using the custome methode findAllByRole
        $role = '["ROLE_ADMIN"]';
        $admins = $userRepository->findAllByRole($role);

        return $this->render('back/user/admin/browse.html.twig', [
            'admins' => $admins,
        ]);
    }

    /**
     * Shows one admin
     * 
     * @Route("/back/admin/read/{id}", name="admin_read", methods={"GET"})
     */
    public function adminRead(User $user)
    {

        return $this->render('back/user/admin/read.html.twig', [
            'admin' => $user,
        ]);
    }

    /**
     * Form to add an admin
     * 
     * @Route("/back/admin/add", name="admin_add")
     */
    public function adminAdd(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        // the entity to create
        $admin = new User();

        // generates form
        $form = $this->createForm(UserType::class, $admin);

        // we inspect the request and map the datas posted on the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We encode the User's password that's inside our variable $admin
            $hashedPassword = $passwordEncoder->encodePassword($admin, $admin->getPassword());
            // We reassing the encoded password in the User object via $admin
            $admin->setPassword($hashedPassword);

            // saves the new user
            $entityManager->persist($admin);
            $entityManager->flush();

            // Redirects to list 
            return $this->redirectToRoute('back_admin_browse');
        }

        return $this->render('back/user/admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Form to Edit an admin
     * 
     * @Route("/back/admin/edit/{id}", name="admin_edit", methods={"GET","POST"})
     */
    public function adminEdit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Creates and returns a Form instance from the type of the form (UserType).
        $form = $this->createForm(UserType::class, $user);

        // The user's password will be overwritten by $request 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If the form's password field is not empty 
            // that means we want to change it !
            if ($form->get('password')->getData() !== '') {

                $hashedPassword = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
            }

            // Saves the edits 
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_admin_browse');
        }

        return $this->render('back/user/admin/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("back/admin/delete/{id}", name="admin_delete", methods={"DELETE"})
     */
    public function adminDelete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // 404 ?
        if ($user === null) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // @see https://symfony.com/doc/current/security/csrf.html#generating-and-checking-csrf-tokens-manually
        // We fetch the token's name that we dropped into the form
        $submittedToken = $request->request->get('token');

        // 'delete-{...}' is the same value used in the template to generate the token
        if (!$this->isCsrfTokenValid('delete-admin', $submittedToken)) {
            // We return a 403
            throw $this->createAccessDeniedException('Are you token to me !??!??');
        }

        // Else we delete
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('back_admin_browse');
    }

    /*____  ______ _   _ ______ ______ _____ _____ _____          _____  _____ ______  _____ 
    |  _ \|  ____| \ | |  ____|  ____|_   _/ ____|_   _|   /\   |  __ \|_   _|  ____|/ ____|
    | |_) | |__  |  \| | |__  | |__    | || |      | |    /  \  | |__) | | | | |__  | (___  
    |  _ <|  __| | . ` |  __| |  __|   | || |      | |   / /\ \ |  _  /  | | |  __|  \___ \ 
    | |_) | |____| |\  | |____| |     _| || |____ _| |_ / ____ \| | \ \ _| |_| |____ ____) |
    |____/|______|_| \_|______|_|    |_____\_____|_____/_/    \_\_|  \_\_____|______|_____/  
    */

    /**
     * Admins list
     * 
     * @Route("/back/beneficiary/browse", name="back_beneficiary_browse", methods={"GET"})
     */
    public function beneficiaryBrowse(UserRepository $userRepository): Response
    {
        // We fetch the user by its role using the custome methode findAllByRole
        $role = '["ROLE_BENEFICIARY"]';
        $beneficiaries = $userRepository->findAllByRole($role);

        return $this->render('back/user/beneficiary/browse.html.twig', [
            'beneficiaries' => $beneficiaries,
        ]);
    }

    /**
     * Shows one admin
     * 
     * @Route("/back/beneficiary/read/{id}", name="beneficiary_read", methods={"GET"})
     */
    public function beneficiaryRead(User $user)
    {

        return $this->render('back/user/beneficiary/read.html.twig', [
            'beneficiary' => $user,
        ]);
    }

    /**
     * Form to add an admin
     * 
     * @Route("/back/beneficiary/add", name="beneficiary_add")
     */
    public function beneficiaryAdd(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        // the entity to create
        $beneficiary = new User();

        // generates form
        $form = $this->createForm(UserType::class, $beneficiary);

        // we inspect the request and map the datas posted on the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We encode the User's password that's inside our variable $beneficiary
            $hashedPassword = $passwordEncoder->encodePassword($beneficiary, $beneficiary->getPassword());
            // We reassing the encoded password in the User object via $admin
            $beneficiary->setPassword($hashedPassword);

            // saves the new user
            $entityManager->persist($beneficiary);
            $entityManager->flush();

            // Redirects to list 
            return $this->redirectToRoute('back_beneficiary_browse');
        }

        return $this->render('back/user/beneficiary/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Form to Edit an admin
     * 
     * @Route("/back/beneficiary/edit/{id}", name="beneficiary_edit", methods={"GET","POST"})
     */
    public function beneficiaryEdit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Creates and returns a Form instance from the type of the form (UserType).
        $form = $this->createForm(UserType::class, $user);

        // The user's password will be overwritten by $request 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // If the form's password field is not empty 
            // that means we want to change it !
            if ($form->get('password')->getData() !== '') {

                $hashedPassword = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
                $user->setPassword($hashedPassword);
            }

            // Saves the edits 
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_beneficiary_browse');
        }

        return $this->render('back/user/beneficiary/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("back/beneficiary/delete/{id}", name="beneficiary_delete", methods={"DELETE"})
     */
    public function beneficiaryDelete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // 404 ?
        if ($user === null) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // @see https://symfony.com/doc/current/security/csrf.html#generating-and-checking-csrf-tokens-manually
        // We fetch the token's name that we dropped into the form
        $submittedToken = $request->request->get('token');

        // 'delete-{...}' is the same value used in the template to generate the token
        if (!$this->isCsrfTokenValid('delete-beneficiary', $submittedToken)) {
            // We return a 403
            throw $this->createAccessDeniedException('Are you token to me !??!??');
        }

        // Else we delete
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('back_beneficiary_browse');
    }
}
