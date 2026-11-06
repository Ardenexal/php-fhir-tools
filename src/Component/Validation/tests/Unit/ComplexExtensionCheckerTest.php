<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtension;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtensionRecorder;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\PatAnimalExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Validation\ComplexExtensionChecker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;

/**
 * The slice wording is copied from the reference outcome
 * vendor/fhir/fhir-test-cases/validator/outcomes/java/R4.patient-extension-complex-bad1-base.json,
 * less the package version on the canonical, which the generated models do not carry.
 */
#[CoversClass(ComplexExtensionChecker::class)]
final class ComplexExtensionCheckerTest extends TestCase
{
    public function testMissingRequiredSubExtensionIsReportedWithTheReferenceWording(): void
    {
        $xml = <<<'XML'
            <Patient xmlns="http://hl7.org/fhir">
              <extension url="http://hl7.org/fhir/StructureDefinition/patient-animal">
                <extension url="breed">
                  <valueCodeableConcept><text value="Poodle"/></valueCodeableConcept>
                </extension>
              </extension>
              <active value="true"/>
            </Patient>
            XML;

        $resource   = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($xml);
        $violations = (new ComplexExtensionChecker())->check($resource);

        self::assertCount(1, $violations);
        self::assertSame('error', $violations[0]->severity);
        self::assertSame('extension[0]', $violations[0]->path);
        self::assertSame(
            "Slice 'Extension.extension:species': a matching slice is required, but not found "
            . '(from http://hl7.org/fhir/StructureDefinition/patient-animal). '
            . 'Note that other slices are allowed in addition to this required slice',
            $violations[0]->message,
        );
    }

    public function testCompleteComplexExtensionIsNotReported(): void
    {
        $json = '{"resourceType":"Patient","extension":[{"url":"http://hl7.org/fhir/StructureDefinition/patient-animal",'
            . '"extension":[{"url":"species","valueCodeableConcept":{"text":"Dog"}}]}]}';

        $resource = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);

        self::assertSame([], (new ComplexExtensionChecker())->check($resource));
    }

    /**
     * A refusal that names no slice is still reported, in the class's own words, so it cannot pass as clean.
     */
    public function testRefusalWithoutASliceIsReportedWithItsReason(): void
    {
        $extension = new Extension(url: 'http://hl7.org/fhir/StructureDefinition/patient-animal');
        $patient   = new PatientResource(extension: [$extension]);
        IncompleteComplexExtensionRecorder::record($extension, new IncompleteComplexExtension(PatAnimalExtension::class, null, 'species must be a known code'));

        $violations = (new ComplexExtensionChecker())->check($patient);

        self::assertCount(1, $violations);
        self::assertSame('extension[0]', $violations[0]->path);
        self::assertSame(
            'The extension http://hl7.org/fhir/StructureDefinition/patient-animal does not match its definition: species must be a known code',
            $violations[0]->message,
        );
    }

    public function testUndeclaredSliceIsReportedWithTheReferenceWording(): void
    {
        $violations = $this->undeclaredViolationsFor('{"url":"species-x","valueCodeableConcept":{"text":"nothing"}}');

        self::assertCount(1, $violations);
        self::assertSame('extension[0].extension[1]', $violations[0]->path);
        self::assertSame(
            "Sub-extension url 'species-x' is not defined by the Extension http://hl7.org/fhir/StructureDefinition/patient-animal",
            $violations[0]->message,
        );
    }

    /**
     * An absolute url names another extension, which the open slicing of Extension.extension
     * allows; where it may appear is the extension-context check's business.
     */
    public function testNestedExtensionWithAnAbsoluteUrlIsNotReported(): void
    {
        self::assertSame([], $this->undeclaredViolationsFor('{"url":"http://example.org/ext/other","valueString":"x"}'));
    }

    /**
     * The generator types each slice by its first allowed type, so a declared slice holding
     * another allowed type is left over without being undefined.
     */
    public function testDeclaredSliceWithAnUnmodelledValueTypeIsNotReported(): void
    {
        self::assertSame([], $this->undeclaredViolationsFor('{"url":"breed","valueString":"Poodle"}'));
    }

    /**
     * @return list<FHIRValidationViolation>
     */
    private function undeclaredViolationsFor(string $secondSubExtension): array
    {
        $json = '{"resourceType":"Patient","extension":[{"url":"http://hl7.org/fhir/StructureDefinition/patient-animal",'
            . '"extension":[{"url":"species","valueCodeableConcept":{"text":"Dog"}},' . $secondSubExtension . ']}]}';

        $patient = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertInstanceOf(PatAnimalExtension::class, $patient->extension[0]);

        return (new ComplexExtensionChecker())->checkUndeclaredSubExtensions($patient->extension[0], 'extension[0]');
    }
}
