<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/back/department", name="back_department")
     */
    public function index(): Response
    {
        return $this->render('back/department/index.html.twig', [
            'controller_name' => 'DepartmentController',
        ]);
    }
}
