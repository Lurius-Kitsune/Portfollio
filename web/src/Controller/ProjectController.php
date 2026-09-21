<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectContent;
use App\Entity\ProjectContentTranslation;
use App\Entity\ProjectMedia;
use App\Form\ProjectContentType;
use App\Form\ProjectEditType;
use App\Form\ProjectMediaType;
use App\Repository\ProjectContentRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProjectController extends AbstractController
{
    #[Route('/project/{slug}', name: 'project_show', methods: ['GET'])]
    public function show(
        string $slug,
        ProjectRepository $projectRepository,
    ): Response {

        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if ($project === null | ((!$project->isVisible() || !$project->isReadable()) && !$this->isGranted('ROLE_ADMIN'))) {
            $response = $this->render('pages/404.html.twig');
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            return $response;
        }

        $form = $this->createForm(
            ProjectEditType::class,
            $project,
        );

        $contentForms = [];

        foreach ($project->getProjectContents() as $content) {
            $contentForms[$content->getId()] = $this->createForm(
                ProjectContentType::class,
                $content,
                [
                    'action' => $this->generateUrl(
                        'project_content_update',
                        [
                            'slug' => $project->getSlug(),
                            'id' => $content->getId(),
                        ]
                    ),
                    'method' => 'POST',
                    'attr' => [
                        'data-action' => 'submit->project-editor#saveContent',
                    ],
                ],
            );
        }

        $contentFormCreate = $this->createForm(
            ProjectContentType::class,
            new ProjectContent()->setProjectId($project),
            [
                'action' => $this->generateUrl(
                    'project_content_create',
                    [
                        'slug' => $project->getSlug(),
                    ]
                ),
                'method' => 'POST',
                'attr' => [
                    'data-action' => 'submit->project-editor#createContent',
                ],
            ],
        );
        $mediaForm = $this->createForm(
            ProjectMediaType::class,
            new ProjectMedia()->setProjectId($project),
            [
                'action' => $this->generateUrl(
                    'project_content_create',
                    [
                        'slug' => $project->getSlug(),
                    ]
                ),
                'method' => 'POST',
                'attr' => [
                    'data-action' => 'submit->project-editor#createContent',
                ],
            ],
        );
        return $this->render('pages/project/page.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'contentForms' => array_map(
                static fn($form) => $form->createView(),
                $contentForms,
            ),
            'createContentForm' => $contentFormCreate->createView(),
            'mediaForm' => $mediaForm->createView()
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
            return $this->notFoundResponse('Projet introuvable.');
        }

        $form = $this->createForm(
            ProjectEditType::class,
            $project,
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->invalidFormResponse();
        }

        $entityManager->flush();

        // Le slug peut être modifié par le trigger PostgreSQL.
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
            'redirectUrl' => $this->projectRedirectUrl(
                $project,
                $request,
            ),
        ]);
    }

    /*
     * ---------------------------------------------------------
     * HELPERS
     * ---------------------------------------------------------
     */

    private function notFoundResponse(string $message): JsonResponse
    {
        return $this->json(
            [
                'success' => false,
                'message' => $message,
            ],
            Response::HTTP_NOT_FOUND,
        );
    }



    private function invalidFormResponse(): JsonResponse
    {
        return $this->json(
            [
                'success' => false,
                'message' => 'Le formulaire contient des erreurs.',
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }



    private function projectRedirectUrl(
        Project $project,
        ?Request $request,
    ): string {
        return $this->generateUrl(
            'project_show',
            [
                'slug' => $project->getSlug(),
                '_locale' => $request?->getLocale(),
            ],
        );
    }
}
