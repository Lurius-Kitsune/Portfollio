<?php

namespace App\Form;

use App\Entity\ProjectMedia;
use App\Enum\EMediaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectMediaUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('url', TextType::class, [
                'required' => false,
            ])
            ->add('type', EnumType::class, [
                'class' => EMediaType::class,
                'required' => true,
                'choice_label' => fn(EMediaType $type) => match ($type) {
                    EMediaType::MT_IMAGE => 'Image',
                    EMediaType::MT_VIDEO => 'Vidéo',
                },
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
            'required' => false,
            'constraints' => [
                new File(
                    maxSize: '50M',
                    mimeTypes: [
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'image/gif',
                        'video/mp4',
                        'video/webm',
                        'video/ogg',
                        'video/quicktime',
                    ],
                    mimeTypesMessage: 'Veuillez sélectionner une image ou une vidéo valide.',
                ),
                ],
            ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $file = $form->get('file')->getData();
            $url = $form->get('url')->getData();

            if (!$file && !$url) {
                $form->addError(new FormError(
                    'Vous devez fournir soit un fichier, soit une URL.'
                ));
            }

            if ($file && $url) {
                $form->addError(new FormError(
                    'Vous ne pouvez pas fournir à la fois un fichier et une URL.'
                ));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectMedia::class,
        ]);
    }
}