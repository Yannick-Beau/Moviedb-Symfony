<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('releaseDate')
            ->add('duration')
            ->add('poster', UrlType::class)
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                // On a plusieurs choix possbiles (ArrayCollection)
                'multiple' => true,
                // Un élément html par choix                
                'expanded' => true,
                // libellé de l'option
                'choice_label' => 'name',
                // Cette option permet d'écrire une requête custom en lui transmettant le Repository
                // de l'ntité concernée et en retournant un objet QueryBuilder construit pour notre besoin
                // ici : SELECT * FROM genre ORDER BY ASC
                'query_builder' => function (GenreRepository $genre) {
                    return $genre->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
