<?php

namespace App\Controller;

use App\Entity\CareerEntry;
use App\Form\CareerEntryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/career')]
#[IsGranted('ROLE_ADMIN')]
final class CareerEntryController extends AbstractController
{
    #[Route('', name: 'admin_career_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin_panel/career.html.twig', [
            'entries' => $entityManager->getRepository(CareerEntry::class)->findBy([], ['position' => 'ASC', 'startDate' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_career_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'admin_career_edit', methods: ['GET', 'POST'])]
    public function edit(?int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entry = $id === null ? new CareerEntry() : $entityManager->getRepository(CareerEntry::class)->find($id);
        if (!$entry instanceof CareerEntry) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CareerEntryType::class, $entry)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entry);
            $entityManager->flush();
            $this->addFlash('success', 'Entrée du parcours enregistrée.');
            return $this->redirectToRoute('admin_career_index');
        }

        return $this->render('admin_panel/career_edit.html.twig', ['form' => $form, 'entry' => $entry]);
    }

    #[Route('/{id}/delete', name: 'admin_career_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $entry = $entityManager->getRepository(CareerEntry::class)->find($id);
        if (!$entry instanceof CareerEntry) {
            throw $this->createNotFoundException();
        }

        if ($this->isCsrfTokenValid('delete-career-'.$entry->getId(), $request->request->getString('_token'))) {
            $entityManager->remove($entry);
            $entityManager->flush();
            $this->addFlash('success', 'Entrée du parcours supprimée.');
        }

        return $this->redirectToRoute('admin_career_index');
    }
}
