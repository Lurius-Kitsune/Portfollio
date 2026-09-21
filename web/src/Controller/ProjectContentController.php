<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectContent;
use App\Entity\ProjectContentTranslation;
use App\Form\ProjectContentType;
use App\Repository\ProjectContentRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/project/{slug}/content', name: 'project_content_')]
#[IsGranted('ROLE_ADMIN')]
final class ProjectContentController extends AbstractController
{

    #[Route('/{id}', name: 'update', methods: ['POST'])]
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

        if (!$project instanceof Project) {
            return $this->notFoundResponse('Projet introuvable.');
        }

        $content = $contentRepository->find($id);

        if (!$content instanceof ProjectContent) {
            return $this->notFoundResponse('Contenu introuvable.');
        }

        if ($content->getProjectId() !== $project) {
            return $this->forbiddenResponse(
                "Le contenu n'appartient pas à ce projet."
            );
        }

        $form = $this->createForm(
            ProjectContentType::class,
            $content,
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->invalidFormResponse();
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'content' => $this->contentData($content),
            'redirectUrl' => $this->projectRedirectUrl(
                $project,
                $request,
            ),
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteContent(
        string $slug,
        int $id,
        ProjectRepository $projectRepository,
        ProjectContentRepository $contentRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $project = $projectRepository->findOneBy([
            'slug' => $slug,
        ]);

        if (!$project instanceof Project) {
            return $this->notFoundResponse('Projet introuvable.');
        }

        $content = $contentRepository->find($id);

        if (!$content instanceof ProjectContent) {
            return $this->notFoundResponse('Contenu introuvable.');
        }

        if ($content->getProjectId() !== $project) {
            return $this->forbiddenResponse(
                "Le contenu n'appartient pas à ce projet."
            );
        }

        $entityManager->remove($content);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'redirectUrl' => $this->projectRedirectUrl(
                $project,
                $request,
            ),
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function createContent(
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

        $content = new ProjectContent();
        $content->setProjectId($project);

        $form = $this->createForm(
            ProjectContentType::class,
            $content,
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->invalidFormResponse();
        }

        $entityManager->persist($content);
        $entityManager->flush();

        $this->createTranslation(
            $content,
            $entityManager,
        );

        return $this->json([
            'success' => true,
            'content' => $this->contentData($content),
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

    private function contentData(
        ProjectContent $content,
    ): array {
        return [
            'id' => $content->getId(),
            'title' => $content->getTitle(),
            'themeName' => $content->getThemeName(),
            'content' => $content->getContent(),
        ];
    }

    private function createTranslation(
        ProjectContent $content,
        EntityManagerInterface $entityManager,
    ): void {
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
                    json_encode(
                        $value,
                        JSON_THROW_ON_ERROR,
                    )
                );
            } else {
                $translation->setContent($value ?? '');
            }

            $entityManager->persist($translation);
        }

        $entityManager->flush();
    }

    private function forbiddenResponse(string $message): JsonResponse
    {
        return $this->json(
            [
                'success' => false,
                'message' => $message,
            ],
            Response::HTTP_FORBIDDEN,
        );
    }
}
