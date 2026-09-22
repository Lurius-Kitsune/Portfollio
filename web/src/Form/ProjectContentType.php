<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectContent;
use App\Entity\ProjectMedia;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ProjectContent|null $projectContent */
        $projectContent = $options['data'] ?? null;
        $project = $projectContent?->getProjectId();

        $builder
            ->add('themeName')
            ->add('title')
            ->add('content', HiddenType::class)
            ->add('projectId', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
            ])
            ->add('linkMedia', EntityType::class, [
                'class' => ProjectMedia::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
            'label' => false,
            'choice_attr' => function (ProjectMedia $media) {
                return [
                    'data-url' => $media->getUrl(),
                    'data-name' => $media->getName(),
                    'data-type' => $media->getType()->value,
                ];
            },
            'query_builder' => function (EntityRepository $er) use ($project) {
                $qb = $er->createQueryBuilder('m');
                if ($project !== null) {
                    $qb->where('m.projectId = :project')
                        ->setParameter('project', $project);
                }
                return $qb->orderBy('m.name', 'ASC');
            },
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
