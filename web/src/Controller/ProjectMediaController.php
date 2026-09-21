<?php

namespace App\Controller;

use App\Entity\ProjectMedia;
use App\Enum\EMediaType;
use App\Form\ProjectMediaUploadType;
use App\Repository\ProjectMediaRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/project/{slug}/media', name: 'project_media_')]
#[IsGranted('ROLE_ADMIN')]
final class ProjectMediaController extends AbstractController
{
    #[Route('/upload', name: 'upload', methods: ['POST'])]
    public function upload(
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
            ], Response::HTTP_NOT_FOUND);
        }

        $media = new ProjectMedia();
        $media->setProjectId($project);

        $form = $this->createForm(ProjectMediaUploadType::class, $media);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'success' => false,
                'message' => 'Le fichier est invalide.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var UploadedFile $file */
        $file = $form->get('file')->getData();

        $filename = uniqid('media_', true)
            . '.'
            . $file->guessExtension();

        $file->move(
            $this->getParameter('project_media_directory'),
            $filename
        );

        $media
            ->setUrl('/uploads/projects/media/' . $filename)
            ->setType(
                str_starts_with($file->getMimeType(), 'video/')
                    ? EMediaType::MT_VIDEO
                    : EMediaType::MT_IMAGE
            );

        $entityManager->persist($media);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'media' => [
                'id' => $media->getId(),
                'name' => $media->getName(),
                'url' => $media->getUrl(),
                'type' => $media->getType()->value,
            ],
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        string $slug,
        ProjectRepository $projectRepository,
        ProjectMediaRepository $mediaRepository,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public')] string $publicDirectory
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

        $media = $mediaRepository->find($id);

        if (!$media || $media->getProjectId()?->getId() !== $project->getId()) {
            return $this->json([
                'success' => false,
                'message' => 'Média introuvable.',
            ], Response::HTTP_NOT_FOUND);
        }
        $path = $media->getUrl();
        $path = $publicDirectory . '/' . ltrim($path, '/');

        if (is_file($path)) {
            unlink($path);
        }

        $entityManager->remove($media);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'id' => $id,
        ]);
    }
}
