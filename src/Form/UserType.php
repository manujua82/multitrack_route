<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active', CheckboxType::class, [
                'required' => false
            ])
            ->add('name')
            ->add('rols', null, [
                'mapped' => false,
                'required' => false,
                'data' => $options['edit'] ? $options['roles'] : '',
                "row_attr" => [
                    "style" => "display: none;"
                ]
            ])
            ->add('rolegroup', ChoiceType::class, [
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
                'required' => false
            ])
            ->add('roleAllowUserManagment', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowViewOrders', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowDuplicate', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowViewVehicule', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowEditOrders', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowViewDirectories', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowEditCompOrders', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowEditDirectories', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('roleAllowAllShippers', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'edit' => false,
            'roles' => null
        ]);
    }
}
