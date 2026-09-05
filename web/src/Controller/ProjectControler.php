<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectControler extends AbstractController
{
    #[Route('/project/{slug}', name: 'project_show', methods: ['GET'])]
    public function show(string $slug, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneBy(['slug' => $slug]);

        if ($project === null) {
            $response = $this->render('pages/404.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }

        $carouselItems = [
            "https://media1.tenor.com/m/gfuFUR0Nv34AAAAC/f.gif",
            "images/project/fgtb/protoDetectionUIEnemy.gif",
            "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBB4LQTn0vRq4ydPLp-uTj_lEUHOHYWUU18JlCq5KuMw&s=10",
        ];

        return $this->render('pages/project/page.html.twig', [
            'project' => $project,
        ]);
    }
}
