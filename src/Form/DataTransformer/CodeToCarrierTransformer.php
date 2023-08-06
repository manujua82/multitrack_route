<?php

namespace App\Form\DataTransformer;

use App\Entity\Carrier;
use App\Repository\CarrierRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CodeToCarrierTransformer implements DataTransformerInterface
{
    private $carrierRepository;
    private $finderCallback;

    public function __construct(CarrierRepository $carrierRepository, callable $finderCallback)
    {
        $this->carrierRepository = $carrierRepository;
        $this->finderCallback = $finderCallback;
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof Carrier) {
            throw new \LogicException('The CarrierSelectTextType can only be used with Carrier objects');
        }

        return $value->getCode();
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

        $callback = $this->finderCallback;
        $carrier = $callback($this->carrierRepository, $value);

        if (!$carrier) {
            throw new TransformationFailedException(sprintf('No carrier found with code "%s"', $value));
        }

        return $carrier;
    }
}