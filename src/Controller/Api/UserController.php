<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /* ------------------
    --- BENEFICIARIES ---
    -------------------*/


    /**
     * @Route("/beneficiaries", name="api_beneficiaries", methods="GET")
     */
    public function readBeneficiaries(UserRepository $userRepository): Response
    {
        $role = '["ROLE_BENEFICIARY"]';
        $beneficiaries = $userRepository->findAllByRole($role);

        return $this->json(
            $beneficiaries,
            200,
            [],
            ['groups' => 'beneficiaries_read']
        );
    }

    /**
     * @Route("/beneficiaries/{id<\d+>}", name="api_beneficiaries_read_item", methods="GET")
     */
    public function readBeneficiaryItem(UserRepository $userRepository, User $user = null): Response
    {
        if ($user === null) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Bénéficiaire non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        $id = $user->getId();
        $role = '["ROLE_BENEFICIARY"]';
        $beneficiary = $userRepository->findUserById($role, $id);

        if (empty($beneficiary)) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Bénéficiaire non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $beneficiary,
            200,
            [],
            ['groups' => 'beneficiaries_read']
        );
    }


    /* ---------------
    --- VOLUNTEERS ---
    ----------------*/


    /**
     * @Route("/volunteers", name="api_volunteers", methods="GET")
     */
    public function readVolunteers(UserRepository $userRepository): Response
    {
        $role = '["ROLE_VOLUNTEER"]';
        $volunteers = $userRepository->findAllByRole($role);

        return $this->json(
            $volunteers,
            200,
            [],
            ['groups' => 'volunteers_read']
        );
    }

    /**
     * @Route("/volunteers/{id<\d+>}", name="api_volunteers_read_item", methods="GET")
     */
    public function readVolunteersItem(UserRepository $userRepository, User $user = null): Response
    {
        if ($user === null) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Bénévole non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        $id = $user->getId();
        $role = '["ROLE_VOLUNTEER"]';
        $volunteer = $userRepository->findUserById($role, $id);

        if (empty($volunteer)) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Bénévole non trouvé.',
            ];

            // On défini un message custom et un status code HTTP 404
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $volunteer,
            200,
            [],
            ['groups' => 'volunteers_read']
        );
    }


    //  /**
    //  * @Route("/beneficiaries", name="api_beneficiary_create", methods="POST")
    //  */
    // public function createBeneficiary(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    // {
    //     $jsonContent = $request->getContent();

    //     $userData = json_decode($jsonContent, true);

    //     $user = new User();

    //     $form = $this->createForm(UserType::class, $user);

    //     // $form->handleRequest($userData);
    //     $form->submit($userData);

    //     if($form->isSubmitted() && $form->isValid()) {
    //         //dd($form);
    //         $user->setPassword($passwordEncoder->encodePassword($user, $form->getData()->getPassword()));

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute(
    //             'api_beneficiaries_read_item',
    //             ['id' => $user->getId()],
    //             Response::HTTP_CREATED
    //         );

    //     }
    //     $message = $form->getErrors();

    //     return $this->json($message, Response::HTTP_UNPROCESSABLE_ENTITY);

    // }

    /**
     * @Route("/users", name="api_user_create", methods="POST")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();
        $user = $serializer->deserialize($jsonContent, User::class, 'json');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json('Utilisateur créé', Response::HTTP_CREATED);
    }


    /**
     * Edit User(PATCH)
     * 
     * @Route("/users/{id<\d+>}", name="api_users_patch", methods={"PATCH"})
     */
    public function patch(User $user = null, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        // 1. On souhaite modifier le film dont l'id est transmis via l'URL

        // 404 ?
        if ($user === null) {
            // On retourne un message JSON + un statut 404
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Notre JSON qui se trouve dans le body
        $jsonContent = $request->getContent();


        // @todo Pour PATCH, s'assurer qu'on au moins un champ

        $serializer->deserialize(
            $jsonContent,
            User::class,
            'json',
            // On a cet argument en plus qui indique au serializer quelle entité existante modifier
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        // Validation de l'entité désérialisée
        $errors = $validator->validate($user);
        // Génération des erreurs
        if (count($errors) > 0) {
            // On retourne le tableau d'erreurs en Json au front avec un status code 422
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->setUpdatedAt(new \DateTime());
        // On flush $movie qui a été modifiée par le Serializer
        $em->flush();

        // @todo Conditionner le message de retour au cas où
        // l'entité ne serait pas modifiée
        return $this->json(['message' => 'Utilisateur modifié.'], Response::HTTP_OK);
    }
}
