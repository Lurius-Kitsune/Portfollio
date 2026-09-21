<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectContent;
use App\Entity\ProjectMedia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('themeName')
            ->add('title')
            ->add('content')
            ->add('projectId', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
            ])
            ->add('linkMedia', EntityType::class, [
                'class' => ProjectMedia::class,
                'choice_label' => 'id',
                'multiple' => true,
                'required' => false,
            ]);


        $builder
            ->get('content')
            ->addModelTransformer(new CallbackTransformer(
                static function (?array $content): string {
                    return $content
                        ? json_encode($content, JSON_THROW_ON_ERROR)
                        : '';
                },
                static function (?string $content): array {
                    return $content
                        ? json_decode($content, true, 512, JSON_THROW_ON_ERROR)
                        : [];
                },
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectContent::class,
        ]);
    }
}
