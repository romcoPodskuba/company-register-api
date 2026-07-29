<?php

namespace App\Serializer;

use App\DTO\CompanyRegister\Address;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AddressNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Address;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Address::class => true];
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        /** @var Address $object */

        return [
            'houseNumber' => $object->houseNumber,
            'street' => $object->street,
            'city' => $object->city,
            'postalCode' => $object->postalCode,
            'country' => $object->country,
        ];
    }
}
