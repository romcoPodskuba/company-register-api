<?php

namespace App\Service;

class AlgorithmService
{
    private const START = 1;
    private const END = 100;

    public function generate(int $start = self::START, int $end = self::END): array
    {
        $result = [];

        for ($i = $start; $i <= $end; ++$i) {
            $value = '';

            if (!($i % 3)) {
                $value .= 'Super';
            }
            if (!($i % 5)) {
                $value .= 'Faktura';
            }

            $result[] = $value ?: $i;
        }

        return $result;
    }
}
