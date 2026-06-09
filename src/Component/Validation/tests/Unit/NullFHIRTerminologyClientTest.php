<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use PHPUnit\Framework\TestCase;

final class NullFHIRTerminologyClientTest extends TestCase
{
    public function testValidateCodeAlwaysReturnsTrue(): void
    {
        $client = new NullFHIRTerminologyClient();

        self::assertTrue($client->validateCode('http://hl7.org/fhir/ValueSet/any', 'any'));
    }

    public function testValidateCodingAlwaysReturnsTrue(): void
    {
        $client = new NullFHIRTerminologyClient();

        self::assertTrue($client->validateCoding('http://hl7.org/fhir/ValueSet/any', 'http://loinc.org', 'any'));
    }
}
