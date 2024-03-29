<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('contact')
            ->add('email')
            ->add('phone')
            ->add('timeFrom', null, [
                'widget' => 'single_text', 
                'html5' => false,
            ])
            ->add('timeUntil', null, [
                'widget' => 'single_text', 
                'html5' => false,
            ])
            ->add('priority', ChoiceType::class, [
                'choices'  => [
                    'High' => "HIGHT",
                    'Normal' => "NORMAL",
                    'Low' => "LOW",
                ],
            ])
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'label' => ' ',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
