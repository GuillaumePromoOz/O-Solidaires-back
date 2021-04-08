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
     * Get all beneficiaries
     * 
     * @Route("/beneficiaries", name="api_beneficiaries", methods="GET")
     */
    public function readBeneficiaries(UserRepository $userRepository): Response
    {
        // We use a custom method in the UserRepostiry to find all users by their role
        // we provide as a parameter into the function the user's role.
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
     * 
     * Get one beneficiary
     * 
     * @Route("/beneficiaries/{id<\d+>}", name="api_beneficiaries_read_item", methods="GET")
     */
    public function readBeneficiaryItem(UserRepository $userRepository, User $user = null): Response
    {
        // Checks if user is null
        // if user is null then it returns a 404
        if ($user === null) {


            $message = [
                // HTTP status code
                'status' => Response::HTTP_NOT_FOUND,
                // Custom message
                'error' => 'Bénéficiaire non trouvé.',
            ];

            // We define a custom message and generate a HTTP 404 status code
            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        // We verify if the user's ID matches the user's role
        // (as there is THREE separate roles: Beneficiary, Volunteer and Admin)

        $id = $user->getId();
        $role = '["ROLE_BENEFICIARY"]';

        // A custom method was created in the UserRepository
        $beneficiary = $userRepository->findUserById($role, $id);


        // if there is no match, the beneficiary variable is considered empty and returns a 404
        if (empty($beneficiary)) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Bénéficiaire non trouvé.',
            ];

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
     * Get all volunteers
     * i
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
     * Get one volunteer
     * 
     * @Route("/volunteers/{id<\d+>}", name="api_volunteers_read_item", methods="GET")
     */
    public function readVolunteersItem(UserRepository $userRepository, User $user = null): Response
    {
        if ($user === null) {


            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Bénévole non trouvé.',
            ];

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

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $volunteer,
            200,
            [],
            ['groups' => 'volunteers_read']
        );
    }

    /**
     * Add User
     * 
     * @Route("/users", name="api_user_create", methods="POST")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder,  SerializerInterface $serializer, ValidatorInterface $validator)
    {
        //!\ When creating a new user we have to assign it an already existing department via its ID
        //!\ however the serializer has no idea that this ID is linked to the Department Entity 
        //!\ we resolved this issue inside the Normalizer by indicating that the ID is linked to an existing Entity (@see EntityNormalizer.php)

        // We fetch the data sent via method POST into our Symfony Component "Request"
        // the content is in the body of the request
        // the content is stored into variable $jsonContent
        $jsonContent = $request->getContent();

        // We deserialize the JSON content into the User Entity
        // Basically it transforms the json into an object
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // The recieved data is checked for anomalies (ex, empty fields, invalid values)
        // The Validator alidates a property of an object against the constraints specified
        // for this property.
        $errors = $validator->validate($user);

        // Counts the number of errors, if bigger than zero -> returns error 422
        // returns an array of errors to the frontend
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We encode the password
        // setPassword() fetches the password entered by the user ($user->getPassword) that's now been hashed
        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

        // Save
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json('Utilisateur créé', Response::HTTP_CREATED);
    }


    /**
     * Edit User(PATCH)
     * 
     * @Route("/users/{id<\d+>}", name="api_users_patch", methods={"PATCH"})
     */
    public function patchUser(User $user = null, EntityManagerInterface $em, SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, Request $request, ValidatorInterface $validator)
    {
        // Le User courant a-t-il le droits de modifier cette question
        $this->denyAccessUnlessGranted('patchUser', $user);


        if ($user === null) {

            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            User::class,
            'json',
            // This extra argument specifies to the serializer which existing entity to modify
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        //dd($jsonContent['lastname']);
        $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
        //dd($user);
        $user->setUpdatedAt(new \DateTime());
        $em->flush();

        return $this->json(['message' => 'Utilisateur modifié.'], Response::HTTP_OK);
    }

    /**
     * 
     * Get user data
     * 
     * @Route("/connected_user", name="api_user_connected", methods="GET")
     */
    public function connectedUser()
    {
        // JWT replaces our Session which is used to store the user in memory 
        // We get the connected user using the getter getUser() from the Abstract Controller
        $user = $this->getUser();
        // With getter getRoles we go fetch index 0 inside the array which value equals to the user's role
        $role = $user->getRoles()[0];

        // If role matches "volunteer" we send the accessible datas of a volunteer (same goes for beneficiaries and admin)
        if ($role === 'ROLE_VOLUNTEER') {
            return $this->json(
                $user,
                200,
                [],
                ['groups' => 'volunteers_read']
            );
        }
        if ($role === 'ROLE_BENEFICIARY') {
            return $this->json(
                $user,
                200,
                [],
                ['groups' => 'beneficiaries_read']
            );
        }
        if ($role === 'ROLE_ADMIN') {
            //return $this->json($user, 200, [], ['groups' => 'admins_read']);
            return $this->redirectToRoute('admin_browse');
        }
    }
}
