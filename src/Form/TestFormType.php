<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du jeu',
                'required' => false
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du jeu'
            ])
            ->add('pegi', TypeIntegerType::class, [
                'label' => 'Pegi du jeu'
            ])
            ->add('select', ChoiceType::class, [
                'label' => 'La platforme',
                'choices' => [
                    'Ps5' => 1,
                    'X-box' => 2,
                    'Pc' => 3,
                ],
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
