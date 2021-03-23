<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
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
