<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleRedirectSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 100],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest())
            return;

        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // Symfony Web Profiler
        if (str_starts_with($path, '/_wdt') || str_starts_with($path, '/_profiler'))
            return;


        // Une locale est déjà présente
        if (preg_match('#^/(fr|en)(/|$)#', $path, $matches)) {
            $locale = $matches[1];

            // IMPORTANT :
            // On définit la locale AVANT le routing.
            $request->setLocale($locale);
            $request->attributes->set('_locale', $locale);

            return;
        }

        // Fichiers statiques
        if (preg_match('#^/(build|assets|images|css|js|fonts|cv)(/|$)#', $path))
            return;

        // Aucune locale dans l'URL :
        // on choisit la langue préférée du navigateur.
        $locale = $request->getPreferredLanguage(['fr', 'en']) ?: 'fr';

        // Redirection vers la même URL avec la locale
        $url = '/' . $locale . $path;

        if ($request->getQueryString())
            $url .= '?' . $request->getQueryString();

        $event->setResponse(new RedirectResponse($url));
    }
}
