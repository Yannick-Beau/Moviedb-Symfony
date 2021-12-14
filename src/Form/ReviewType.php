<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
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
            ->add('rating', null, [
                'label' => 'Apréciation (note sur 5)'
            ])
            ->add('reactions', null, [
                'label' => 'Ce film vous a fait'
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
