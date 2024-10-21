<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', null, [
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
                'empty_data' => '',
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'United States' => 'US',
                    'France' => 'FR',
                    'Germany' => 'GR',
                    'Spain' => 'ES',
                    'Canada' => 'CA',
                ]
            ])
            ->add('latitude', NumberType::class, [
                'scale' => 7,
                'invalid_message' => 'Please enter a valid latitude.',
                'empty_data' => '0',
            ])
            ->add('longitude', NumberType::class, [
                'scale' => 7,
                'invalid_message' => 'Please enter a valid longitude.',
                'empty_data' => '0',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class
        ]);
    }
}
