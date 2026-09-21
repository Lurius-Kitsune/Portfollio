<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectContent;
use App\Entity\ProjectContentTranslation;
use App\Form\ProjectContentType;
use App\Form\ProjectEditType;
use App\Repository\ProjectContentRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Json;

final class ProjectControler extends AbstractController
{
    #[Route('/project/{slug}', name: 'project_show', methods: ['GET'])]
    public function show(string $slug, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneBy(['slug' => $slug]);
        if (($project === null || ((!$project->isVisible() || !$project->isReadable())) && !$this->isGranted('ROLE_ADMIN'))) {
            $response = $this->render('pages/404.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }
        $form = $this->createForm(ProjectEditType::class, $project);

        $contentForms = [];
        foreach ($project->getProjectContents() as $content) {
            $contentForms[$content->getId()] = $this->createForm(
                ProjectContentType::class,
                $content,
                [
                    'action' => $this->generateUrl('project_content_update', [
                        'slug' => $project->getSlug(),
                        'id' => $content->getId(),
                    ]),
                    'attr' => [
                        'data-action' => 'submit->project-editor#saveContent',
                    ],
                ]
            );
        }
        $contentFormCreate = $this->createForm(ProjectContentType::class, new ProjectContent()->setProjectId($project), [

            'action' => $this->generateUrl('project_content_create', [
                'slug' => $project->getSlug(),
            ]),
            'attr' => [
                'data-action' => 'submit->project-editor#createContent',
            ],
        ]);

        return $this->render('pages/project/page.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'contentForms' => array_map(
                static fn($form) => $form->createView(),
                $contentForms
            ),
            'createContentForm' => $contentFormCreate->createView(),
        ]);
    }

    #[Route('/project/{slug}', name: 'project_patch', methods: ['POST'])]
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

        if (!$project instanceof Project) {
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ProjectEditType::class, $project);


        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'success' => false,
                'message' => 'Le formulaire contient des erreurs.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->flush();
        $entityManager->refresh($project);
        return $this->json([
            'success' => true,
            'project' => [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'role' => $project->getRole(),
                'intro' => $project->getIntro(),
                'conclusionTitle' => $project->getConclusionTitle(),
                'conclusionContent' => $project->getConclusionContent(),
            ],
            'redirectUrl' => $this->generateUrl('project_show', [
                'slug' => $project->getSlug(),
                '_locale' => $request->getLocale(),
            ]),
        ]);
    }

    #[Route('/project/{slug}/content/{id}', name: 'project_content_update', methods: ['POST'])]
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

        if (!$project instanceof Project)
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);

        $content = $contentRepository->find($id);

        if (!$content instanceof ProjectContent) {
            return $this->json([
                'success' => false,
                'message' => 'Content not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($content->getProjectId() !== $project)
            return $this->json([
                'success' => false,
                'message' => "Content doesn't belong to this project",
            ], Response::HTTP_FORBIDDEN);

        $form = $this->createForm(ProjectContentType::class, $content);


        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'success' => false,
                'message' => 'Le formulaire contient des erreurs.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->flush();
        $entityManager->refresh($content);

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'content' => [
                'id' => $content->getId(),
                'title' => $content->getTitle(),
                'themeName' => $content->getThemeName(),
                'content' => $content->getContent(),
            ],
            'redirectUrl' => $this->generateUrl('project_show', [
                'slug' => $project->getSlug(),
                '_locale' => $request->getLocale(),
            ]),
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

        if (!$project instanceof Project)
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);

        $content = $contentRepository->find($id);

        if (!$content instanceof ProjectContent)
            return $this->json([
                'success' => false,
                'message' => 'Contenu introuvable.',
            ], Response::HTTP_NOT_FOUND);

        if ($content->getProjectId() !== $project)
            return $this->json([
                'success' => false,
                'message' => "Content doesn't belong to this project",
            ], Response::HTTP_FORBIDDEN);

        $entityManager->remove($content);
        $entityManager->flush();

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/project/{slug}/content', name: 'project_content_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createContent(
        string $slug,
        Request $request,
        ProjectRepository $projectRepository,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if (!$project instanceof Project)
            return $this->json([
                'success' => false,
                'message' => 'Projet introuvable.',
            ], Response::HTTP_NOT_FOUND);

        $content = new ProjectContent();
        $form = $this->createForm(ProjectContentType::class, $content);


        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'success' => false,
                'message' => 'Le formulaire contient des erreurs.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($content->getProjectId() !== $project)
            return $this->json([
                'success' => false,
                'message' => "Content doesn't belong to this project",
            ], Response::HTTP_FORBIDDEN);
        $entityManager->persist($content);

        // Création du ProjectContent
        $entityManager->flush();

        $translationData = [
            'title' => $content->getTitle(),
            'themeName' => $content->getThemeName(),
            'content' => $content->getContent(),
        ];

        foreach ($translationData as $field => $value) {
            $translation = new ProjectContentTranslation();

            $translation->setLocale('en');
            $translation->setField($field);
            $translation->setForeignKey($content->getId());
            $translation->setObjectClass(ProjectContent::class);

            if ($field === 'content') {
                $translation->setContent(
                    json_encode($value, JSON_THROW_ON_ERROR)
                );
            } else {
                $translation->setContent($value ?? '');
            }

            $entityManager->persist($translation);
        }

        $entityManager->flush();

        //$loopId = $project->getProjectContents()->count() - 1;

        return $this->json([
            'success' => true,
            'content' => [
                'id' => $content->getId(),
                'title' => $content->getTitle(),
                'themeName' => $content->getThemeName(),
                'content' => $content->getContent(),
            ],
            'redirectUrl' => $this->generateUrl('project_show', [
                'slug' => $project->getSlug(),
                '_locale' => $request->getLocale(),
            ]),
        ]);
    }
}
