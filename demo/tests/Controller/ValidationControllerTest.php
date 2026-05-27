<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ValidationControllerTest extends WebTestCase
{
    public function testGetValidatePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/validate');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'FHIR Resource Validator');
    }

    public function testPostValidateWithMinimalPatient(): void
    {
        $client = static::createClient();
        $client->request('POST', '/validate/run', [
            'fhirJson'    => '{"resourceType":"Patient","id":"test"}',
            'fhirVersion' => 'r4',
            'profileUrls' => '',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('[data-result-panel]');
    }

    public function testPostValidateWithInvalidJson(): void
    {
        $client = static::createClient();
        $client->request('POST', '/validate/run', [
            'fhirJson'    => '{not json}',
            'fhirVersion' => 'r4',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.bg-red-50', 'Invalid JSON');
    }

    public function testPostValidateWithUnknownResourceType(): void
    {
        $client = static::createClient();
        $client->request('POST', '/validate/run', [
            'fhirJson'    => '{"resourceType":"UnknownFoo123"}',
            'fhirVersion' => 'r4',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.bg-red-50', 'Unknown resource type');
    }

    public function testPostValidateWithProfileUrl(): void
    {
        $client = static::createClient();
        $client->request('POST', '/validate/run', [
            'fhirJson'    => '{"resourceType":"Patient","id":"test"}',
            'fhirVersion' => 'r4',
            'profileUrls' => 'http://hl7.org/fhir/StructureDefinition/Patient',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorNotExists('[data-error-panel]');
        self::assertSelectorExists('[data-result-panel]');
    }
}
