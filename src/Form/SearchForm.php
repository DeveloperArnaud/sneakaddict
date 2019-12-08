<?php


namespace App\Form;


use App\Data\SearchData;
use App\Entity\Color;
use App\Entity\Taille;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    private $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('q',TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' =>'Rechercher'
            ]
        ])

            ->add('tailles',EntityType::class,[
                'label' => false,
                'required' => false,
                'class' => Taille::class,
                'expanded' => true,
                'multiple' => true,
            ])
        ->add('couleurs',EntityType::class,[
            'label' => false,
            'required' => false,
            'class' => Color::class,
            'expanded' => true,
            'multiple' => true,
        ])
        ->add('min',NumberType::class, [
            'label' => false,
            'required' =>false,
            'attr' => [
                'placeholder' => 'Prix min'
            ]
        ])
            ->add('max',NumberType::class, [
                'label' => false,
                'required' =>false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])
        ->add('valider',SubmitType::class, ['attr' => ['value' => 'Valider', 'class' => 'btn btn-dark w-100']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
            return '';
    }


}