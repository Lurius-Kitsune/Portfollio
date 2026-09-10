<?php

namespace App\Controller;

use App\Entity\HardSkillType;
use App\Entity\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage',  methods: ['GET'])]
    public function index(TranslatorInterface $_translator, EntityManagerInterface $entityManager): Response
    {
        return $this->render('pages/home/page.html.twig', [
            "projects" => $entityManager->getRepository(ProjectType::class)->findAll(),
            "skillGroups" => $entityManager->getRepository(HardSkillType::class)->findAll(),
        ]);
    }
}
