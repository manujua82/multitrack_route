<?php

namespace App\Form;

use App\Entity\MainCompany;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('billingEmail')
            ->add('phone')
            ->add('website')
            ->add('address')
            ->add('city')
            ->add('country')
            ->add('contact');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MainCompany::class,
        ]);
    }
}
