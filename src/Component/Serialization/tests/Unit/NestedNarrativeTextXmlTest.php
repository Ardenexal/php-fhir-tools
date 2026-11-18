<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PHPUnit\Framework\TestCase;

/**
 * Narrative text survives XML when the resource is reached through an array property.
 *
 * `XmlEncoder` puts an element's text under `#` once the element has an attribute, so
 * `<div lang="en-NZ">Name</div>` decodes to `['@lang' => 'en-NZ', '#' => 'Name']`. Array properties
 * strip XML meta keys from their whole subtree, and that took the narrative text with them: a Bundle
 * entry's `<div lang="en-NZ">Name: James Judge</div>` came out as `<div lang="en-NZ"/>`. Found on
 * `R4.ips-link-xml`, whose narratives the validator then judged empty.
 */
final class NestedNarrativeTextXmlTest extends TestCase
{
    public function testAttributedNarrativeInsideABundleEntryKeepsItsText(): void
    {
        $patient = $this->entryPatient('<div lang="en-NZ" xmlns="http://www.w3.org/1999/xhtml">Name: James Judge</div>');

        self::assertSame('<div lang="en-NZ">Name: James Judge</div>', $patient->text?->div?->value);
    }

    public function testAttributedChildElementInsideABundleEntryKeepsItsText(): void
    {
        $patient = $this->entryPatient('<div xmlns="http://www.w3.org/1999/xhtml"><span id="n1">James</span></div>');

        self::assertSame('<div><span id="n1">James</span></div>', $patient->text?->div?->value);
    }

    private function entryPatient(string $div): PatientResource
    {
        $xml = '<Bundle xmlns="http://hl7.org/fhir"><type value="collection"/><entry><resource><Patient>'
            . '<text><status value="generated"/>' . $div . '</text>'
            . '</Patient></resource></entry></Bundle>';

        $bundle = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($xml);

        self::assertInstanceOf(BundleResource::class, $bundle);
        $patient = $bundle->entry[0]->resource ?? null;
        self::assertInstanceOf(PatientResource::class, $patient);

        return $patient;
    }
}
