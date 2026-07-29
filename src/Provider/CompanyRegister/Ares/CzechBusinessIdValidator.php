<?php

namespace App\Provider\CompanyRegister\Ares;

use App\Exception\CompanyRegister\InvalidBusinessIdException;
use App\Provider\CompanyRegister\BusinessIdValidatorInterface;

final class CzechBusinessIdValidator implements BusinessIdValidatorInterface
{
    public function validate(string $businessId): void
    {
        $businessId = preg_replace('/\s+/', '', $businessId);

        if (!ctype_digit($businessId) || strlen($businessId) !== 8) {
            throw new InvalidBusinessIdException('Business ID must be 8 digits long');
        }

        $checksum = $this->calculateChecksum($businessId);

        if ($checksum !== (int) $businessId[7]) {
            throw new InvalidBusinessIdException('Invalid business ID checksum');
        }
    }

    private function calculateChecksum(string $businessId): int
    {
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $sum += (int) $businessId[$i] * (8 - $i);
        }

        return match ($sum % 11) {
            0 => 1,
            1 => 0,
            default => 11 - ($sum % 11)
        };
    }
}
