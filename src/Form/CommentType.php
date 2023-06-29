<?php

namespace App\Form;

use App\Entity\Comment;
use Cassandra\Type\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('created_at')
            ->add('user', null, [
                'label' => 'Utilisateur',
                'choice_label' => 'pseudo',
            ])
            ->add('trick')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
