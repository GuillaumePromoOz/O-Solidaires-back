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
        $beneficiaries = $userRepository->findAllBeneficiaries($role);

        return $this->json(
            $beneficiaries,
            200,
            [],
            ['groups' => 'beneficiaries_read']
        );
    }
}
