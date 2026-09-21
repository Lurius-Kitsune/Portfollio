<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\TagsToStringTransformer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProjectEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('start_year', null, [
                'widget' => 'single_text',
            ])
            ->add('end_date', null, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('thumbnail_url')
            ->add('link')
            ->add('tags', TextType::class, [
                'required' => false,
                'empty_data' => '',
            ])
            ->add('role')
            ->add('intro')
            ->add('conclusionTitle')
            ->add('conclusionContent', HiddenType::class)
            ->add('isVisible')
            ->add('isReadable')
            ->add('type', EntityType::class, [
                'class' => ProjectType::class,
                'choice_label' => 'name',
            ]);

        $builder
            ->get('tags')
            ->addModelTransformer(new TagsToStringTransformer());

        $builder
            ->get('conclusionContent')
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
            'data_class' => Project::class,
        ]);
    }
}
