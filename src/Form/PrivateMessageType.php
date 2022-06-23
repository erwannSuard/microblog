<?php

namespace App\Form;

use App\Entity\PrivateMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PrivateMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Title'
                )
            ])
            ->add('content', null, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Your message, please be kind...'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PrivateMessage::class,
        ]);
    }
}
