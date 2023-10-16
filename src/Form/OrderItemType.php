<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\OrderItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('item', ItemAutocompleteField::class);

        $formModifier = function (FormInterface $form, Item $item = null): void {
            $form->add('unitMeasure',null, [
                'empty_data' => (null === $item) ? "pcs." : $item->getUnit()
            ])
            ->add('qty', null, [
                'empty_data' => 1
            ])
            ->add('price', null, [
                'empty_data' => (null === $item) ? 0.00  : $item->getPrice()
            ])
            ->add('totalAmount', null, [
                'empty_data' => (null === $item) ? 0.00 : $item->getPrice()
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier): void {
                $itemData = $event->getData();
                $formModifier($event->getForm(), $itemData?->getItem());
                
            }
        );

        $builder->get('item')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier): void {                
                $itemData = $event->getForm()->getData();
                $form = $event->getForm()->getParent();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback function!
                $formModifier($form, $itemData);
            }
        );

        // by default, action does not appear in the <form> tag
        // you can set this value by passing the controller route
        $builder->setAction($options['action']);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
        ]);
    }
}
