<?php

namespace App\Controller;

use App\Entity\ProjectContent;
use App\Repository\ProjectContentRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectControler extends AbstractController
{
    #[Route('/project/{slug}', name: 'project_show', methods: ['GET'])]
    public function show(string $slug, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneBy(['slug' => $slug]);
        if (($project === null || !$project->isVisible() || !$project->isReadable()) && !$this->isGranted('ROLE_ADMIN')) {
            $response = $this->render('pages/404.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }

        return $this->render('pages/project/page.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/project/{slug}', name: 'project_patch', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function patchProject(
        string $slug,
        Request $request,
        ProjectRepository $projectRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {

        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if (!$project) {
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json([
                'success' => false,
                'message' => 'Données invalides.',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['name'])) {
            $project->setName($data['name']);
        }

        if (isset($data['role'])) {
            $project->setRole($data['role']);
        }

        if (isset($data['intro'])) {
            $project->setIntro($data['intro']);
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'project' => [
                'name' => $project->getName(),
                'role' => $project->getRole(),
                'intro' => $project->getIntro(),
            ],
        ]);
    }

    #[Route('/project/{slug}/content/{id}', name: 'project_content_update', methods: ['PATCH'])]
    #[IsGranted('ROLE_ADMIN')]
    public function patchContent(
        string $slug,
        int $id,
        ProjectRepository $projectRepository,
        ProjectContentRepository $contentRepository,
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {

        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if (!$project) {
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }

        $content = $contentRepository->find($id);

        if (!$content instanceof ProjectContent) {
            return $this->json([
                'success' => false,
                'message' => 'Contenu introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Très important :
        // vérifier que ce contenu appartient bien à ce projet.
        if ($content->getProjectId() !== $project) {
            return $this->json([
                'success' => false,
                'message' => "Content doesn't belong to this project",
            ], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);

        $content->setTitle($data['title'] ?? $content->getTitle());
        $content->setThemeName($data['themeName'] ?? $content->getThemeName());
        $content->setContent($data['content'] ?? $content->getContent());

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'content' => [
                'id' => $content->getId(),
                'title' => $content->getTitle(),
                'themeName' => $content->getThemeName(),
                'content' => $content->getContent(),
            ],
        ]);
    }

    #[Route('/project/{slug}/content/{id}', name: 'project_content_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteContent(
        string $slug,
        int $id,
        ProjectRepository $projectRepository,
        ProjectContentRepository $contentRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {

        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if (!$project) {
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }

        $content = $contentRepository->find($id);

        if (!$content instanceof ProjectContent) {
            return $this->json([
                'success' => false,
                'message' => 'Contenu introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($content->getProjectId() !== $project) {
            return $this->json([
                'success' => false,
                'message' => "Content doesn't belong to this project",
            ], Response::HTTP_FORBIDDEN);
        }

        $entityManager->remove($content);
        $entityManager->flush();

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route(
        '/project/{slug}/content',
        name: 'project_content_create',
        methods: ['POST']
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function createContent(
        string $slug,
        Request $request,
        ProjectRepository $projectRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if (!$project) {
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json([
                'success' => false,
                'message' => 'Données invalides.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $content = new ProjectContent();

        $content->setProjectId($project);
        $content->setThemeName($data['themeName'] ?? '');
        $content->setTitle($data['title'] ?? '');
        $content->setContent($data['content'] ?? []);

        $entityManager->persist($content);
        $entityManager->flush();
        $loopId = $project->getProjectContents()->count() - 1;
        return $this->render('components/project/content.html.twig', [
            'content' => $content,
            'loopId' => $loopId,
            'project' => $project,
        ]);
    }
}
