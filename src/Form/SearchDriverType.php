<?php

namespace App\Form;

use App\Entity\Driver;
use App\Repository\DriverRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchDriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('query', EntityType::class, [
            'class' => Driver::class,
            'query_builder' => function (DriverRepository $er): QueryBuilder {
                return $er->findAllQuery();
            }
        ]);
    }
}