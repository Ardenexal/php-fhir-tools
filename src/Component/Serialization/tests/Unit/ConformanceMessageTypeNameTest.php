<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Profile\ShareableActivityDefinitionProfile;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRConformanceViolationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * A conformance message must name the FHIR type, not the PHP class that implements it.
 *
 * These messages are compared against the HL7 Java reference validator by the conformance corpus, so
 * a label the spec does not contain is not cosmetic -- it is a diff against the oracle.
 *
 * Both cases here are ones where the PHP class name and the FHIR type name genuinely differ. That
 * matters more than it sounds: `AbstractFHIRNormalizer::shortTypeName()` falls back to the PHP short
 * name, and for the complex types anyone reaches for first -- `Coding`, `Quantity`, `Identifier` --
 * the fallback is indistinguishable from the right answer. A version of that method which *always*
 * fell through to the class name shipped and survived, because nothing exercised a type whose two
 * names disagree.
 */
final class ConformanceMessageTypeNameTest extends TestCase
{
    /**
     * An element-level complex type is named with its published dots, not its flattened class name.
     *
     * `Dosage.doseAndRate` is generated as `DosageDoseAndRate` because a PHP class name cannot hold a
     * dot. 44 generated classes across R4/R4B/R5 are shaped this way, and they are the only complex
     * types whose message label can be wrong.
     */
    public function testAnElementLevelComplexTypeIsNamedWithItsPublishedDots(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        // `Dosage.doseAndRate.type` is 0..1; repeating it is what trips the cardinality guard.
        $xml = <<<'XML'
            <MedicationRequest xmlns="http://hl7.org/fhir">
              <status value="active"/>
              <intent value="order"/>
              <dosageInstruction>
                <doseAndRate>
                  <type><text value="a"/></type>
                  <type><text value="b"/></type>
                </doseAndRate>
              </dosageInstruction>
            </MedicationRequest>
            XML;

        $this->expectException(FHIRConformanceViolationException::class);
        $this->expectExceptionMessage('Dosage.doseAndRate.type: max allowed = 1, but found 2');

        $service->deserialize($xml);
    }

    /**
     * A document that resolves to a typed profile subclass is still reported as the base type.
     *
     * `meta.profile` makes the deserializer pick `ShareableActivityDefinitionProfile`, and that class
     * is what the guard receives. Reporting it verbatim would send a reader hunting the spec for a
     * type that does not exist; `#[FHIRProfile(baseType:)]` carries the name they can actually look
     * up.
     */
    public function testAProfileResolvedDocumentIsReportedAsItsBaseType(): void
    {
        $service = $this->serviceResolvingCoreProfiles();

        $json = '{"resourceType":"ActivityDefinition","status":"draft",'
            . '"meta":{"profile":["http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition"]},'
            . '"identifier":{"value":"x"}}';

        $this->expectException(FHIRConformanceViolationException::class);
        $this->expectExceptionMessage('The property identifier must be a JSON Array, not an Object (at ActivityDefinition)');

        $service->deserialize($json);
    }

    /**
     * The guard for the test above: without this the profile assertion passes for the wrong reason.
     *
     * If `meta.profile` resolution silently stopped working, the deserializer would fall back to
     * `ActivityDefinitionResource`, whose own `#[FhirResource(type:)]` also answers
     * `ActivityDefinition` -- so the message would still read correctly while testing nothing. This
     * pins that the profile subclass really is the class the guard sees.
     */
    public function testTheProfileFixtureReallyResolvesToTheProfileSubclass(): void
    {
        $service = $this->serviceResolvingCoreProfiles();

        $valid = '{"resourceType":"ActivityDefinition","status":"draft",'
            . '"meta":{"profile":["http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition"]},'
            . '"identifier":[{"value":"x"}]}';

        self::assertInstanceOf(ShareableActivityDefinitionProfile::class, $service->deserialize($valid));
    }

    /**
     * A service whose IG registry is pointed at the generated core profile classes.
     *
     * `createDefault()` scans extension directories only, so no profile URL resolves under it and the
     * profile path cannot be reached. Pointing the IG scanner at the shipped `R4/Profile` output is
     * the cheapest way to exercise it without a generated IG tree; the directory is located from the
     * fixture class rather than by counting `..` segments up from this file.
     */
    private function serviceResolvingCoreProfiles(): FHIRSerializationService
    {
        $file = (new \ReflectionClass(ShareableActivityDefinitionProfile::class))->getFileName();
        self::assertIsString($file, 'the profile fixture must be a real, loadable file');

        return FHIRSerializationService::createWithIG(
            dirname($file),
            'Ardenexal\FHIRTools\Component\Models\R4\Profile',
            FhirVersion::R4,
        );
    }
}
