<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\Extract;

use Ardenexal\FHIRTools\Component\Sdc\Tests\Integration\AbstractSdcConformanceTest;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Validates the conformance-oracle comparison contract for `$extract`-shaped output (a transaction
 * Bundle), driving {@see AbstractSdcConformanceTest} through the extract-specific concerns: entry
 * count, per-entry `resourceType` and key properties, `request.method`, and `urn:uuid:` reference
 * topology normalisation.
 *
 * These are harness self-tests. Reference-seeded `$extract` conformance cases (vendored golden
 * Bundles) arrive with the `sdc-extract` feature plan; this file also keeps the `sdc-extract-spec`
 * test-suite wired and non-empty until then.
 */
final class SdcExtractOracleHarnessTest extends AbstractSdcConformanceTest
{
    public function testAcceptsDifferentUuidsWithMatchingReferenceTopology(): void
    {
        $expected = $this->bundle('urn:uuid:11111111-1111-1111-1111-111111111111', 'urn:uuid:22222222-2222-2222-2222-222222222222');
        // Same topology (Observation.subject -> the Patient entry), different random UUIDs.
        $actual = $this->bundle('urn:uuid:aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'urn:uuid:bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb');

        $this->assertSdcConformance($expected, $actual);
    }

    public function testPreservesEntryCount(): void
    {
        $expected = $this->bundle('urn:uuid:11111111-1111-1111-1111-111111111111', 'urn:uuid:22222222-2222-2222-2222-222222222222');
        $actual   = $expected;
        // Drop the Observation entry -> entry count differs -> must fail.
        array_pop($actual['entry']);

        $this->expectException(ExpectationFailedException::class);
        $this->assertSdcConformance($expected, $actual);
    }

    public function testPreservesRequestMethod(): void
    {
        $expected                                = $this->bundle('urn:uuid:11111111-1111-1111-1111-111111111111', 'urn:uuid:22222222-2222-2222-2222-222222222222');
        $actual                                  = $expected;
        $actual['entry'][0]['request']['method'] = 'PUT'; // reference impl used POST

        $this->expectException(ExpectationFailedException::class);
        $this->assertSdcConformance($expected, $actual);
    }

    /**
     * A minimal $extract-style transaction Bundle: a Patient entry and an Observation entry whose
     * subject references the Patient by fullUrl.
     *
     * @return array<string, mixed>
     */
    private function bundle(string $patientUrl, string $observationUrl): array
    {
        return [
            'resourceType' => 'Bundle',
            'type'         => 'transaction',
            'entry'        => [
                [
                    'fullUrl'  => $patientUrl,
                    'resource' => ['resourceType' => 'Patient', 'name' => [['family' => 'Smith']]],
                    'request'  => ['method' => 'POST', 'url' => 'Patient'],
                ],
                [
                    'fullUrl'  => $observationUrl,
                    'resource' => [
                        'resourceType' => 'Observation',
                        'status'       => 'final',
                        'subject'      => ['reference' => $patientUrl],
                    ],
                    'request' => ['method' => 'POST', 'url' => 'Observation'],
                ],
            ],
        ];
    }
}
