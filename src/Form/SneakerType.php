<?php

namespace App\Form;

use App\Entity\Sneaker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('path',TextType::class,['label' => 'Image','data_class'=> null])
            ->add('taille')
            ->add('save', SubmitType::class, ['label' => 'Valider'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sneaker::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App:Sneaker';
    }

}
