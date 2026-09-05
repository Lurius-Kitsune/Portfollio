<?php

namespace App\Controller;

use App\Entity\HardSkillType;
use App\Entity\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage',  methods: ['GET'])]
    public function index(TranslatorInterface $_translator, EntityManagerInterface $entityManager): Response
    {

        /*$projects = [
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
        */

        $skillGroups = [
            [
                'title' => $_translator->trans('skills.web', domain: 'homepage'),
                'icon' => '&lt;/&gt;',
                'items' => [
                    ['label' => 'HTML5', 'short' => '5', 'color' => 'bg-white text-slate-900'],
                    ['label' => 'CSS3', 'short' => '3', 'color' => 'bg-sky-100 text-sky-700'],
                    ['label' => 'JavaScript', 'short' => 'JS', 'color' => 'bg-yellow-100 text-yellow-700'],
                    ['label' => 'PHP', 'short' => 'php', 'color' => 'bg-indigo-100 text-indigo-700'],
                    ['label' => 'WordPress', 'short' => 'WP', 'color' => 'bg-slate-200 text-slate-800'],
                    ['label' => 'Shopify', 'short' => 'Sf', 'color' => 'bg-emerald-100 text-emerald-700'],
                ],
            ],
            [
                'title' => $_translator->trans('skills.application', domain: 'homepage'),
                'icon' => '&lt;/&gt;',
                'items' => [
                    ['label' => 'Python', 'short' => 'Py', 'color' => 'bg-orange-100 text-orange-700'],
                    ['label' => 'C#', 'short' => 'C#', 'color' => 'bg-violet-100 text-violet-700'],
                    ['label' => 'C++', 'short' => 'C++', 'color' => 'bg-violet-100 text-violet-700'],
                ],
            ],
            [
                'title' => $_translator->trans('skills.engine', domain: 'homepage'),
                'icon' => '3D',
                'items' => [
                    ['label' => 'Unity', 'short' => 'U', 'color' => 'bg-indigo-100 text-indigo-700'],
                    ['label' => 'Unreal Engine', 'short' => 'UE', 'color' => 'bg-blue-100 text-blue-700'],
                ],
            ],
            [
                'title' => $_translator->trans('skills.tools', domain: 'homepage'),
                'icon' => '&lt;/&gt;',
                'items' => [
                    ['label' => 'Git', 'short' => 'Git', 'color' => 'bg-pink-100 text-pink-700'],
                    ['label' => 'GitHub', 'short' => 'GH', 'color' => 'bg-slate-200 text-slate-800'],
                    ['label' => 'GitHubActions', 'short' => 'GHA', 'color' => 'bg-slate-200 text-slate-800'],
                    ['label' => 'Docker', 'short' => 'D', 'color' => 'bg-cyan-100 text-cyan-700'],
                    ['label' => 'Notion', 'short' => 'N', 'color' => 'bg-gray-200 text-gray-700'],
                    ['label' => 'Trello', 'short' => 'T', 'color' => 'bg-sky-100 text-sky-700'],
                ],
            ],
        ];

        return $this->render('pages/home/page.html.twig', [
            "projects" => $entityManager->getRepository(ProjectType::class)->findAll(),
            "skillGroups" => $entityManager->getRepository(HardSkillType::class)->findAll(),
        ]);
    }

    public function redirectToLocale(Request $request): RedirectResponse
    {
        $language = $request->getPreferredLanguage(['fr', 'en']) ?: 'fr';

        return $this->redirectToRoute('homepage', ['_locale' => $language]);
    }
}
