<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'homperagez')]
    public function index(Request $request): Response
    {
        $this->setLanguage($request);

        return $this->render('base.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    #[Route('/test', name: 'app_homepage')]
    public function test(Request $request): Response
    {
        $this->setLanguage($request);

        return $this->render('base.html.twig', [
            'controller_name' => $_REQUEST['name'] ?? 'HomepageController',
        ]);
    }

    private function setLanguage(Request $request): void
    {
        $language = $request->query->get('lang');

        if (in_array($language, ['fr', 'en'], true)) {
            $request->getSession()->set('_locale', $language);
        } else {
            $language = $request->getSession()->get('_locale');

            if (!in_array($language, ['fr', 'en'], true)) {
                $language = $request->getPreferredLanguage(['fr', 'en']) ?: 'fr';
                $request->getSession()->set('_locale', $language);
            }
        }

        $request->setLocale($language);
    }
}
