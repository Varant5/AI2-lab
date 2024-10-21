<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\MeasurementData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('wind_speed', null, [
                'empty_data' => '0'
            ])
            ->add('humidity', null, [
                'empty_data' => '0'
            ])
            ->add('pressure', null, [
                'empty_data' => '0'
            ])
            ->add('temperature', null, [
                'empty_data' => '0'
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeasurementData::class,
        ]);
    }
}
