<?php

namespace App\Form;

use App\Entity\CareerEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CareerEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, ['choices' => ['Parcours scolaire' => 'education', 'Expérience professionnelle' => 'experience']])
            ->add('title', TextType::class, ['label' => 'Intitulé'])
            ->add('organization', TextType::class, ['label' => 'École ou employeur'])
            ->add('startDate', DateType::class, ['label' => 'Date de début', 'widget' => 'single_text'])
            ->add('endDate', DateType::class, ['label' => 'Date de fin (laisser vide si en cours)', 'widget' => 'single_text', 'required' => false])
            ->add('description', TextareaType::class, ['label' => 'Description', 'required' => false])
            ->add('position', IntegerType::class, ['label' => 'Ordre d’affichage']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CareerEntry::class]);
    }
}
