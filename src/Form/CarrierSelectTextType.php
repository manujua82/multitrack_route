<?php

namespace App\Form;

use App\Form\DataTransformer\CodeToCarrierTransformer;
use App\Repository\CarrierRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class CarrierSelectTextType extends AbstractType
{
    private $carrierRepository;
    private $router;

    public function __construct(CarrierRepository $carrierRepository, RouterInterface $router)
    {
        $this->carrierRepository = $carrierRepository;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CodeToCarrierTransformer(
            $this->carrierRepository,
            $options['finder_callback']
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'Carrier not found!',
            'finder_callback' => function(CarrierRepository $carrierRepository, string $code) {
                return $carrierRepository->findByCode($code);
            }
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'].' ' : '';
        $class .= 'js-carrier-autocomplete';

        $attr['class'] = $class;
        $attr['data-autocomplete-url'] = $this->router->generate('utilsCarriers');
        $view->vars['attr'] = $attr;
    }

}