<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use PHPUnit\Framework\TestCase;

final class InMemoryFHIRTerminologyClientTest extends TestCase
{
    private const string VS_URL = 'http://hl7.org/fhir/ValueSet/observation-status';

    // -------------------------------------------------------------------------
    // validateCode
    // -------------------------------------------------------------------------

    public function testValidateCodeReturnsTrueForKnownValidCode(): void
    {
        $client = new InMemoryFHIRTerminologyClient([
            self::VS_URL => ['|final' => true],
        ]);

        self::assertTrue($client->validateCode(self::VS_URL, 'final'));
    }

    public function testValidateCodeReturnsFalseForKnownInvalidCode(): void
    {
        $client = new InMemoryFHIRTerminologyClient([
            self::VS_URL => ['|unknown' => false],
        ]);

        self::assertFalse($client->validateCode(self::VS_URL, 'unknown'));
    }

    public function testValidateCodeReturnsDefaultResultForUnknownUrl(): void
    {
        $client = new InMemoryFHIRTerminologyClient([]);

        self::assertTrue($client->validateCode(self::VS_URL, 'anything'));
    }

    public function testValidateCodeDefaultResultFalseMakesUnknownCodesFail(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        self::assertFalse($client->validateCode(self::VS_URL, 'anything'));
    }

    // -------------------------------------------------------------------------
    // validateCoding
    // -------------------------------------------------------------------------

    public function testValidateCodingReturnsTrueForKnownValidSystemCode(): void
    {
        $client = new InMemoryFHIRTerminologyClient([
            self::VS_URL => ['http://loinc.org|8867-4' => true],
        ]);

        self::assertTrue($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    public function testValidateCodingReturnsFalseForKnownInvalidSystemCode(): void
    {
        $client = new InMemoryFHIRTerminologyClient([
            self::VS_URL => ['http://loinc.org|bad-code' => false],
        ]);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', 'bad-code'));
    }

    public function testValidateCodingReturnsDefaultResultForUnknownUrl(): void
    {
        $client = new InMemoryFHIRTerminologyClient([]);

        self::assertTrue($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }

    public function testValidateCodingDefaultResultFalseMakesUnknownFail(): void
    {
        $client = new InMemoryFHIRTerminologyClient([], false);

        self::assertFalse($client->validateCoding(self::VS_URL, 'http://loinc.org', '8867-4'));
    }
}
