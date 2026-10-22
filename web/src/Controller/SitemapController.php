<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SitemapController extends AbstractController
{
    public function index(
        ProjectRepository $projectRepository,
        UrlGeneratorInterface $urlGenerator,
    ): Response {
        $canonicalBaseUrl = rtrim((string) $this->getParameter('app.canonical_base_url'), '/');
        $locales = ['fr', 'en'];
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        $homePaths = [];
        foreach ($locales as $locale) {
            $homePaths[$locale] = $urlGenerator->generate(
                'homepage',
                ['_locale' => $locale],
                UrlGeneratorInterface::ABSOLUTE_PATH,
            );
        }

        $xml .= $this->localizedUrlEntries($canonicalBaseUrl, $homePaths);

        $projects = $projectRepository->findBy(
            ['isVisible' => true, 'isReadable' => true],
            ['slug' => 'ASC'],
        );

        foreach ($projects as $project) {
            $slug = $project->getSlug();
            if ($slug === null || $slug === '') {
                continue;
            }

            $projectPaths = [];
            foreach ($locales as $locale) {
                $projectPaths[$locale] = $urlGenerator->generate(
                    'project_show',
                    ['_locale' => $locale, 'slug' => $slug],
                    UrlGeneratorInterface::ABSOLUTE_PATH,
                );
            }

            $xml .= $this->localizedUrlEntries($canonicalBaseUrl, $projectPaths);
        }

        $xml .= "</urlset>\n";

        return new Response($xml, Response::HTTP_OK, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * @param array<string, string> $paths Paths keyed by locale.
     */
    private function localizedUrlEntries(string $canonicalBaseUrl, array $paths): string
    {
        $alternates = $paths + ['x-default' => $paths['fr']];
        $xml = '';

        foreach ($paths as $path) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . $this->escapeXml($canonicalBaseUrl . $path) . "</loc>\n";

            foreach ($alternates as $locale => $alternatePath) {
                $xml .= '    <xhtml:link rel="alternate" hreflang="' . $this->escapeXml($locale) . '" href="'
                    . $this->escapeXml($canonicalBaseUrl . $alternatePath) . '" />' . "\n";
            }

            $xml .= "  </url>\n";
        }

        return $xml;
    }

    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
