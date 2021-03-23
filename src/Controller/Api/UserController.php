<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

     
}
