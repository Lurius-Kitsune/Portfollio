<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjectControler extends AbstractController
{
    #[Route('/project/{id}', name: 'project_show', methods: ['GET'])]
    public function show(string $id, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($id);

        if ($project === null) {
            $response = $this->render('pages/404.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }

        return $this->render('pages/project/show.html.twig', [
            'project' => $project,
        ]);
    }
}
