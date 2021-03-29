<?php

namespace App\Controller\Back;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepartmentController extends AbstractController
{
    /**
     * Get all departments
     * 
     * @Route("/back/department/browse", name="back_department_browse", methods={"GET"})
     */
    public function browse(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();

        return $this->render('back/department/browse.html.twig', [
            'departments' => $departments,
        ]);
    }

    /**
     * Get one department
     * 
     * @Route("/back/department/read/{id<\d+>}", name="back_department_read", methods="GET")
     */
    public function read(Department $department = null, UserRepository $userRepository): Response
    {
        if ($department === null) {
            throw $this->createNotFoundException('Département non trouvé.');
        }
        $id = $department->getId();
        $roleBeneficiary =  '["ROLE_BENEFICIARY"]';
        $roleVolunteer = '["ROLE_VOLUNTEER"]';
        $roleAdmin = '["ROLE_ADMIN"]';

        $admins = $userRepository->findUserByDepartmentAndRole($roleAdmin, $id);
        $volunteers = $userRepository->findUserByDepartmentAndRole($roleVolunteer, $id);
        $beneficiaries = $userRepository->findUserByDepartmentAndRole($roleBeneficiary, $id);

        //dd($department);
        return $this->render('back/department/read.html.twig',[
            'department' => $department,
            'admins' => $admins,
            'volunteers' => $volunteers,
            'beneficiaries' => $beneficiaries,
        ]);
    }
}
