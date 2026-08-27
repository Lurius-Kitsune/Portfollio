<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $this->setLanguage($request);

        $projects = [
            [
                'id' => 'nellie-the-seer',
                'title' => 'Nelli The Seerrrr',
                'type' => 'Personal / Academic',
                'role' => 'Developer / Designer',
                'year' => 2024,
                'image' => 'https://cdn.akamai.steamstatic.com/steam/apps/3801320/header.jpg',
                'thumbnail' => 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/257157405/b7ebd9925b04223bf18ab13da37d209f60e3fce4/movie_600x337.jpg?t=1750751029',
                'link' => 'https://store.steampowered.com/app/3801320/Nelli_The_Seer/',
                'video' => 'https://video.akamai.steamstatic.com/store_trailers/3801320/1617221277/053ea1a7278b337c5b70162b32fb585cf27c1773/1750144262/hls_264_master.m3u8?t=1750751029',
                'description' => 'Narrative puzzle/adventure available on Steam. Trailer and assets available on the store page.',
                'tags' => ['Unity', 'C#', 'Narrative', 'Puzzle']
            ],
            [
                'id' => 'la-poste-vr',
                'title' => 'La Poste VR (Serious Game)',
                'type' => 'Professional',
                'role' => 'C# / Unity Developer',
                'year' => 2023,
                'image' => '',
                'link' => '#',
                'description' => 'Serious game developed with Unity and C# for La Poste — training and immersive scenarios.',
                'tags' => ['Unity', 'C#', 'VR', 'Serious Game']
            ]
        ];


        return $this->render('pages/homepage.html.twig', [
            "projects" => $projects
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
