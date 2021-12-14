<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Critique',
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'Apréciation',
                'choices' => [
                    // Label => valeur
                    'Execellent' => 5,
                    'Trés bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1,
                ],
                'placeholder' => 'Choisissez une apréciation',
                // Multiple et expanded permet de choisir la façon d'afficher les choix
                // Plusieur choix possibles ou non
                'multiple' => false,
                // Chaque choix à son widget html ou non
                'expanded' => false,
            ])
            ->add('reactions', ChoiceType::class, [
                'label' => 'Ce film vous a fait',
                'choices' => [
                    'Rire' => 'smile',
                    'Pleurer' => 'cry',
                    'Réfléchir' => 'think',
                    'Dormir' => 'sleep',
                    'Rêver' => 'dream',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('watchedAt', null, [
                'label' => 'Vous avez vu ce film le'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
