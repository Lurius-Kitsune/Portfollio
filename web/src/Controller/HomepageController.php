<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'homperagez')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    #[Route('/test', name: 'app_homepage')]
    public function test(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => $_REQUEST['name'] ?? 'HomepageController',
        ]);
    }
}
