<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * M04: `answerValueSet`-bound choice items render a free-text code input plus a "Check code" action
 * instead of the M02-promised-but-never-built disabled placeholder. Covers all three outcomes: no
 * terminology server configured, a valid code, and an invalid code.
 */
final class SdcValidateCodeTest extends WebTestCase
{
    private function questionnaireJson(): string
    {
        return json_encode([
            'resourceType' => 'Questionnaire',
            'status'       => 'active',
            'item'         => [[
                'linkId'         => 'maritalStatus',
                'type'           => 'choice',
                'text'           => 'Marital status',
                'answerValueSet' => 'http://hl7.org/fhir/ValueSet/marital-status',
            ]],
        ], JSON_THROW_ON_ERROR);
    }

    public function testNoTerminologyServerConfiguredShowsNotConfiguredNote(): void
    {
        $client = static::createClient();

        $client->request('POST', '/sdc/validate-code', [
            'questionnaireJson' => $this->questionnaireJson(),
            'answers'           => ['maritalStatus' => 'M'],
            'checkCodeLinkId'   => 'maritalStatus',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'No terminology server configured');
    }

    public function testConfiguredTerminologyServerReportsValidCode(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRTerminologyClientInterface::class, new StubFHIRTerminologyClient(true));

        $client->request('POST', '/sdc/validate-code', [
            'questionnaireJson' => $this->questionnaireJson(),
            'answers'           => ['maritalStatus' => 'M'],
            'checkCodeLinkId'   => 'maritalStatus',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Valid: "M"');
    }

    public function testConfiguredTerminologyServerReportsInvalidCode(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRTerminologyClientInterface::class, new StubFHIRTerminologyClient(false));

        $client->request('POST', '/sdc/validate-code', [
            'questionnaireJson' => $this->questionnaireJson(),
            'answers'           => ['maritalStatus' => 'ZZ'],
            'checkCodeLinkId'   => 'maritalStatus',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Invalid: "ZZ"');
    }

    public function testBlankCodeIsNotCheckedYet(): void
    {
        $client = static::createClient();
        self::getContainer()->set(FHIRTerminologyClientInterface::class, new StubFHIRTerminologyClient(true));

        $client->request('POST', '/sdc/validate-code', [
            'questionnaireJson' => $this->questionnaireJson(),
            'answers'           => ['maritalStatus' => ''],
            'checkCodeLinkId'   => 'maritalStatus',
        ]);

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Enter a code, then click "Check code"');
    }
}
