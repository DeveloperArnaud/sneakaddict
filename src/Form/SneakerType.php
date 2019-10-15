<?php

namespace App\Form;

use App\Entity\Sneaker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SneakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('couleur')
            ->add('modele')
            ->add('titre')
            ->add('description')
            ->add('marque')
            ->add('prix')
            ->add('path')
            ->add('tailles')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sneaker::class,
        ]);
    }
}
