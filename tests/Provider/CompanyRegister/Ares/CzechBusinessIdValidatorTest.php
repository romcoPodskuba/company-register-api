<?php

namespace App\Tests\Provider\CompanyRegister\Ares;

use App\Exception\CompanyRegister\InvalidBusinessIdException;
use App\Provider\CompanyRegister\Ares\CzechBusinessIdValidator;
use PHPUnit\Framework\TestCase;

class CzechBusinessIdValidatorTest extends TestCase
{
    private CzechBusinessIdValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CzechBusinessIdValidator();
    }

    /**
     * @dataProvider provideValidBusinessIds
     */
    public function testValidateAcceptsValidBusinessId(string $businessId): void
    {
        $this->expectNotToPerformAssertions();

        $this->validator->validate($businessId);
    }

    public static function provideValidBusinessIds(): iterable
    {
        yield 'valid ico' => ['01569651'];
        yield 'valid ico with spaces' => ['0156 9651 '];
    }

    /**
     * @dataProvider provideInvalidFormatBusinessIds
     */
    public function testValidateRejectsInvalidFormat(string $businessId): void
    {
        $this->expectException(InvalidBusinessIdException::class);
        $this->expectExceptionMessage('Business ID must be 8 digits long');

        $this->validator->validate($businessId);
    }

    public static function provideInvalidFormatBusinessIds(): iterable
    {
        yield 'too short' => ['1234567'];
        yield 'too long' => ['123456789'];
        yield 'contains letter' => ['015A9651'];
        yield 'empty string' => [''];
    }

    public function testValidateRejectsInvalidChecksum(): void
    {
        $this->expectException(InvalidBusinessIdException::class);
        $this->expectExceptionMessage('Invalid business ID checksum');

        $this->validator->validate('01569650');
    }
}
