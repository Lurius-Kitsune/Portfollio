<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
final class AdminPanelController extends AbstractController
{
    #[Route('/images', name: 'app_admin_panel')]
    public function index(): Response
    {
        return $this->render('admin_panel/index.html.twig');
    }
}
