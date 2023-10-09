<?php

namespace App\Form;

use Symfony\UX\Dropzone\Form\DropzoneType;
use App\Entity\MainCompany;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', DropzoneType::class, ["required" => false])
            ->add('dateFormat', ChoiceType::class, [
                'choices'  => [
                    'dd.MM.yyyy' => "(dd.MM.yyyy)",
                    'yyyy.MM.dd' => "(yyyy.MM.dd)",
                    'MM/dd/yyyy' => "(MM/dd/yyyy)",
                    'dd/MM/yyyy' => "(dd/MM/yyyy)",
                ],
            ])
            ->add('timeFormat', ChoiceType::class, [
                'choices'  => [
                    '12H' => "12 H",
                    '24H' => "24 H",
                ],
            ])
            ->add('unitDistance', ChoiceType::class, [
                'choices'  => [
                    'Miles' => "Miles",
                    'Kilometers' => "Kilometers",
                ],
            ])
            ->add('unitWeight', ChoiceType::class, [
                'choices'  => [
                    'Lb' => "Lb",
                    'Kg' => "Kg",
                ],
            ])
            ->add('unitVolume', ChoiceType::class, [
                'choices'  => [
                    'Pkg' => "Pkg",
                    'm3' => "m3",
                    'Box' => "Box",
                    'gal' => "gal",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainCompany::class,
        ]);
    }
}
