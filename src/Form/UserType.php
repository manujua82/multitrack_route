<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('name', null, [
                'mapped' => false,
            ])
            ->add('rolegroup', ChoiceType::class, [
                'mapped' => false,
                'choices'  => [
                    "View only" => 'view-only',
                    "Dispatcher" => 'dispatcher',
                    "Carrier" => 'carrier',
                    "Shipper Manager" => 'shipper-manager',
                    "Admin" => 'admin',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please provide a valid email',
                    ]),
                    new Email([
                        "message" => "Your email doesn't seems to be valid",
                    ])
                ]
            ])
            ->add('roleAllowViewRoute', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowUserManagment', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowViewOrders', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowDuplicate', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowViewVehicule', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowEditOrders', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowViewDirectories', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowEditCompOrders', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowEditDirectories', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('roleAllowAllShippers', CheckboxType::class, [
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

}