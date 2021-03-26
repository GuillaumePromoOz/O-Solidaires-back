<?php

namespace App\Controller\Api;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepartmentController extends AbstractController
{
    /**
     * Get all departments
     * 
     * @Route("/departments", name="api_departments_read", methods="GET")
     */
    public function read(DepartmentRepository $departmentRepository): Response
    {
        $departments = $departmentRepository->findAll();

        return $this->json($departments, 200, [], ['groups' => 'departments_read']);
    }

    /**
     * Get one department
     * 
     * @Route("/departments/{id<\d+>}", name="api_departments_read_item", methods="GET")
     */
    public function readItem(Department $department = null): Response
    {

        // Checks for errors
        if ($department === null) {

            $message = [
                'status' => Response::HTTP_NOT_FOUND,
                'error' => 'Departement non trouvé.',
            ];

            return $this->json($message, Response::HTTP_NOT_FOUND);
        }

        return $this->json($department, 200, [], ['groups' => [
            'departments_read',
        ]]);
    }
}
