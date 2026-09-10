<?php

namespace App\EventSubscriber;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleDbSubscriber implements EventSubscriberInterface
{
    private TranslatableListener $translatableListener;

    public function __construct(
        TranslatableListener $translatableListener,
    ) {
        $this->translatableListener = $translatableListener;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest())
            return;

        $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
    }
}
