<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectMedia;
use App\Enum\EMediaType;
use App\Form\ProjectMediaUploadType;
use App\Repository\ProjectMediaRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        #[Autowire('%kernel.project_dir%/public')] string $publicDirectory
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

        $media = new ProjectMedia();
        $form = $this->createForm(ProjectMediaUploadType::class, $media);
        $form->handleRequest($request);
        $media->setProjectId($project);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'success' => false,
                'message' => 'Le fichier ou l\'URL est invalide.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var UploadedFile|null $file */
        $file = $form->get('file')->getData();

        if ($file) {
            $filename = uniqid('media_', true) . '.' . $file->guessExtension();
            $type = $media->getType() == EMediaType::MT_IMAGE ? "images" : "video";
            $url = "/{$type}/{$project->getSlug()}/";
            try {
                $file->move(
                    $publicDirectory . $url,
                    $file->getClientOriginalName()
                );
            } catch (FileException $th) {
                return $this->json([
                    'success' => false,
                    'message' => "{$th->getMessage()}",
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $media->setUrl($url . $file->getClientOriginalName());
        }


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
            'html' => $this->renderView("components/project/media.html.twig", ['projectMedia' => $media, 'project' => $project])
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
