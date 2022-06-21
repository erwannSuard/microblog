<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Your catchy title'
                )
            ])
            ->add('articleContent', null, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Your article...'
                )
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'tagName',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
