<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckBoxController extends AbstractController
{
    /**
     * @Route("/check/box", name="app_check_box")
     */
    public function index(): Response
    {
        return $this->render('check_box/index.html.twig', [
            'controller_name' => 'CheckBoxController',
        ]);
    }
}
