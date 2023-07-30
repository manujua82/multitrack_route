<?php

namespace App\Form;

use App\Entity\Driver;
use App\Repository\DriverRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('driver', EntityType::class, [
            'class' => Driver::class,
            'query_builder' => function (DriverRepository $er) use ($options): QueryBuilder {
                return $er->findAllQuery($options['query']);
            }
        ]); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'query' => false,
        ]);

        $resolver->setAllowedTypes('query', 'string');

    }
}