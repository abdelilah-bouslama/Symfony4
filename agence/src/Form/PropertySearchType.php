<?php

namespace App\Form;

use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'required' => false,
                'label' => null,
                'attr' => [
                    'placeholder' => 'product title'
                ]
            ])
            ->add('minSurface', TextType::class,[
                'required' => false,
                'label' => null,
                'attr' => [
                    'placeholder' => 'Min surface'
                ]
            ])
            ->add('maxPrice', TextType::class,[
                'required' => false,
                'label' => null,
                'attr' => [
                    'placeholder' => 'Max Price'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'GET',
            'csrf_protection' => false
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
