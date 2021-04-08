<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /*             _____  __  __ _____ _   _  
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
     * Show one admin
     * 
     * @Route("/back/admin/read/{id}", name="back_admin_read", methods={"GET"})
     */
    public function adminRead(User $user, UserRepository $userRepository)
    {
        $id = $user->getId();
        $role = '["ROLE_ADMIN"]';

        // A custom method was created in the UserRepository
        $admin = $userRepository->findUserById($role, $id);


        // if there is no match, the beneficiary variable is considered empty and returns a 404
        if (empty($admin)) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
        return $this->render('back/user/admin/read.html.twig', [
            'admin' => $user,
        ]);
    }

    /*____  ______ _   _ ______ ______ _____ _____ _____          _____  _____ ______  _____ 
    |  _ \|  ____| \ | |  ____|  ____|_   _/ ____|_   _|   /\   |  __ \|_   _|  ____|/ ____|
    | |_) | |__  |  \| | |__  | |__    | || |      | |    /  \  | |__) | | | | |__  | (___  
    |  _ <|  __| | . ` |  __| |  __|   | || |      | |   / /\ \ |  _  /  | | |  __|  \___ \ 
    | |_) | |____| |\  | |____| |     _| || |____ _| |_ / ____ \| | \ \ _| |_| |____ ____) |
    |____/|______|_| \_|______|_|    |_____\_____|_____/_/    \_\_|  \_\_____|______|_____/  
    */

    /**
     * Beneficiaries list
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
     * Show one beneficiary
     * 
     * @Route("/back/beneficiary/read/{id}", name="back_beneficiary_read", methods={"GET"})
     */
    public function beneficiaryRead(User $user, UserRepository $userRepository)
    {
        $id = $user->getId();
        $role = '["ROLE_BENEFICIARY"]';

        // A custom method was created in the UserRepository
        $beneficiary = $userRepository->findUserById($role, $id);


        // if there is no match, the beneficiary variable is considered empty and returns a 404
        if (empty($beneficiary)) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('back/user/beneficiary/read.html.twig', [
            'beneficiary' => $user,
        ]);
    }

    /* 
    __      ______  _     _    _ _   _ _______ ______ ______ _____   _____ 
    \ \    / / __ \| |   | |  | | \ | |__   __|  ____|  ____|  __ \ / ____|
     \ \  / / |  | | |   | |  | |  \| |  | |  | |__  | |__  | |__) | (___  
      \ \/ /| |  | | |   | |  | | . ` |  | |  |  __| |  __| |  _  / \___ \ 
       \  / | |__| | |___| |__| | |\  |  | |  | |____| |____| | \ \ ____) |
        \/   \____/|______\____/|_| \_|  |_|  |______|______|_|  \_\_____/ 
    */

    /**
     * Volunteers list
     * 
     * @Route("/back/volunteer/browse", name="back_volunteer_browse", methods={"GET"})
     */
    public function volunteersBrowse(UserRepository $userRepository): Response
    {
        // We fetch the user by its role using the custome methode findAllByRole
        $role = '["ROLE_VOLUNTEER"]';
        $volunteers = $userRepository->findAllByRole($role);

        return $this->render('back/user/volunteer/browse.html.twig', [
            'volunteers' => $volunteers,
        ]);
    }

    /**
     * Show one volunteer
     * 
     * @Route("/back/volunteer/read/{id}", name="back_volunteer_read", methods={"GET"})
     */
    public function volunteerRead(User $user, UserRepository $userRepository)
    {
        $id = $user->getId();
        $role = '["ROLE_VOLUNTEER"]';

        // A custom method was created in the UserRepository
        $volunteer = $userRepository->findUserById($role, $id);


        // if there is no match, the beneficiary variable is considered empty and returns a 404
        if (empty($volunteer)) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('back/user/volunteer/read.html.twig', [
            'volunteer' => $user,
        ]);
    }

    /*
 _    _  _____ ______ _____             _____  _____     ________ _____ _____ _______  _______  ______ _      ______ _______ ______ 
| |  | |/ ____|  ____|  __ \      /\   |  __ \|  __ \   / /  ____|  __ \_   _|__   __|/ /  __ \|  ____| |    |  ____|__   __|  ____|
| |  | | (___ | |__  | |__) |    /  \  | |  | | |  | | / /| |__  | |  | || |    | |  / /| |  | | |__  | |    | |__     | |  | |__   
| |  | |\___ \|  __| |  _  /    / /\ \ | |  | | |  | |/ / |  __| | |  | || |    | | / / | |  | |  __| | |    |  __|    | |  |  __|  
| |__| |____) | |____| | \ \   / ____ \| |__| | |__| / /  | |____| |__| || |_   | |/ /  | |__| | |____| |____| |____   | |  | |____ 
 \____/|_____/|______|_|  \_\ /_/    \_\_____/|_____/_/   |______|_____/_____|  |_/_/   |_____/|______|______|______|  |_|  |______|
    */

    /**
     * Form to add a user
     * @var UploadedFile $uploadedFile 
     * @Route("/back/user/add", name="back_user_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, SluggerInterface $slugger)
    {
        // the entity to create
        $user = new User();

        // generates form
        $form = $this->createForm(UserType::class, $user);

        // we inspect the request and map the datas posted on the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We encode the User's password that's inside our variable $admin
            $hashedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            // We reassing the encoded password in the User object via $admin
            $user->setPassword($hashedPassword);

            //Uploading a picture
            //@see https://symfony.com/doc/current/controller/upload_file.html
            /*$uploadedFile = $form->get('picture')->getData();

            // this condition is needed because the 'picture' field is not required
            // so the picture file must be processed only when a file is uploaded
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $uploadedFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // @todo... handle exception if something happens during file upload
                }

                // updates the 'pictureFilename' property to store the picture file name
                // instead of its contents
                $user->setPictureFilename($newFilename);
                $user->setPicture($newFilename);
            }*/

            // saves the new user
            $entityManager->persist($user);
            $entityManager->flush();

            // Flash
            $this->addFlash('success', 'Utilisateur créé avec succès !');

            $role = $user->getRoles()[0];

            if ($role === "ROLE_ADMIN") {
                // Redirects to list 
                return $this->redirectToRoute('back_admin_browse');
            }
            if ($role === "ROLE_BENEFICIARY") {
                // Redirects to list 
                return $this->redirectToRoute('back_beneficiary_browse');
            }
            if ($role === "ROLE_VOLUNTEER") {
                // Redirects to list 
                return $this->redirectToRoute('back_volunteer_browse');
            }
        }

        return $this->render('back/user/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Form to Edit a user
     * 
     * @Route("/back/user/edit/{id}", name="back_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
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

            // Sets the new datetime in the database updated_at field
            $user->setUpdatedAt(new \DateTime());

            // Saves the edits 
            $this->getDoctrine()->getManager()->flush();

            // Flash
            $this->addFlash('warning', 'Utilisateur modifié !');

            $role = $user->getRoles()[0];

            if ($role === "ROLE_ADMIN") {
                // Redirects to list 
                return $this->redirectToRoute('back_admin_browse');
            }
            if ($role === "ROLE_BENEFICIARY") {
                // Redirects to list 
                return $this->redirectToRoute('back_beneficiary_browse');
            }
            if ($role === "ROLE_VOLUNTEER") {
                // Redirects to list 
                return $this->redirectToRoute('back_volunteer_browse');
            }
        }

        return $this->render('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete a user
     * 
     * @Route("back/user/delete/{id}", name="back_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // 404 ?
        if ($user === null) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // @see https://symfony.com/doc/current/security/csrf.html#generating-and-checking-csrf-tokens-manually
        // We fetch the token's name that we dropped into the form
        $submittedToken = $request->request->get('token');

        // 'delete-{...}' is the same value used in the template to generate the token
        if (!$this->isCsrfTokenValid('delete', $submittedToken)) {
            // We return a 403
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        // Else we delete
        $entityManager->remove($user);
        $entityManager->flush();

        // Flash
        $this->addFlash('danger', 'Utilisateur supprimé !');

        $role = $user->getRoles()[0];

        if ($role === "ROLE_ADMIN") {
            // Redirects to list 
            return $this->redirectToRoute('back_admin_browse');
        }
        if ($role === "ROLE_BENEFICIARY") {
            // Redirects to list 
            return $this->redirectToRoute('back_beneficiary_browse');
        }
        if ($role === "ROLE_VOLUNTEER") {
            // Redirects to list 
            return $this->redirectToRoute('back_volunteer_browse');
        }
    }

    /**
     * @Route("/back/theme", name="back_theme")
     */
    /* public function theme(SessionInterface $session)
    {
        // Définir le thème dark en session si pas présent
        if ($session->get('theme') == null) {
            // Définissons un attribut de session, disons 'theme' à 'dark' (clé => valeur)
            $session->set('theme', 'dark'); // $_SESSION['theme'] = 'dark';
        } else {
            // Sinon, on le supprime
            $session->remove('theme');
        }

        // On redirige vers la home

        return $this->redirectToRoute('home');
    }*/
}
