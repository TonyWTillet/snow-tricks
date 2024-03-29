<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options:[
                'invalid_message' => 'Le titre du trick est invalide.',
                'label' => 'Titre du trick',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('content', options:[
                'invalid_message' => 'Le contenu du trick est invalide.',
                'label' => 'Contenu du trick',
                'attr' => [
                    'rows' => '10',
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'invalid_message' => 'La catégorie du trick est invalide.',
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie du trick',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.id', 'ASC');
                },
            ])
            ->add('pictures', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('videos',CollectionType::class, options: [
                'label' => false,
                'allow_extra_fields' => true,
                'mapped' => false,
                'required' => false,
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype_name' => '0',
                'prototype_options'  => [
                    'label' => 'Ajouter une vidéo embed de la plateforme de votre choix (Youtube, Dailymotion, Vimeo, etc...)',
                    'attr' => [
                        'label' => false,
                        'class' => 'form-control',
                    ],
                    'required' => false,
                    'help' => '',
                ],
            ])
            ->add('default_picture', options: [
                'label' => false,
                'allow_extra_fields' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }

}
