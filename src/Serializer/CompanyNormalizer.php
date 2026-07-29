<?php

namespace App\Serializer;

use App\DTO\CompanyRegister\Company;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CompanyNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Company;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Company::class => true];
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        /** @var Company $object */

        return [
            'businessId' => $object->businessId,
            'taxId' => $object->taxId,
            'name' => $object->name,
            'address' => $this->normalizer->normalize($object->address, $format, $context),
            'dateCreated' => $object->dateCreated->format('Y-m-d'),
        ];
    }
}
