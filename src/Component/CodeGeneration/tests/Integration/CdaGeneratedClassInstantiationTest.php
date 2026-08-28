<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Integration;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\InfrastructureRoot;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * End-to-end regression guard for the generated CDA logical-model classes: a subclass must be
 * instantiable AND expose its inherited properties. Without each generated constructor forwarding
 * to parent::__construct(), inherited promoted properties are never initialised and reading them
 * throws "Typed property ... must not be accessed before initialization". Asserting on real,
 * committed generated classes is the only check that reproduces that original symptom — a
 * generator-unit assertion on the emitted source does not.
 *
 * @coversNothing
 */
final class CdaGeneratedClassInstantiationTest extends TestCase
{
    public function testCoreSubclassInitialisesInheritedProperties(): void
    {
        $ii = new II();

        // nullFlavor is inherited from ANY; uninitialised without parent::__construct forwarding.
        self::assertNull($ii->nullFlavor);
    }

    public function testAuSubclassExtendsCoreParentAndInitialisesInheritedProperties(): void
    {
        $document = new AuClinicalDocument();

        self::assertInstanceOf(ClinicalDocument::class, $document, 'AU specialization must extend its core parent');
        // classCode is inherited from ClinicalDocument with the fixed default 'DOCCLIN'.
        self::assertSame('DOCCLIN', $document->classCode);
    }

    /**
     * The wrapper classes synthesized from anonymous nested element groups. CDA declares these
     * inline rather than as named types, so they exist only because the generator lifts them out;
     * each is listed explicitly, so a wrapper silently disappearing from generation fails here.
     *
     * The payload property is the child the wrapper exists to carry, and the expected type is the
     * class it must be typed at — never the generic InfrastructureRoot, which is what the property
     * degraded to while the nested subtree was being dropped.
     *
     * The last flag marks a repeating payload. PHP has no generics, so a repeating element is an
     * `array` and its item class lives in the property metadata instead; only `actReference.id`
     * repeats.
     *
     * @return array<string, array{0: class-string, 1: string, 2: class-string, 3: bool}>
     */
    public static function nestedWrapperProvider(): array
    {
        $clinical = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\ClinicalClass\\';
        $datatype = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\DataType\\';

        return [
            'structured body component' => [$clinical . 'StructuredBodyComponent', 'section', $clinical . 'Section', false],
            'section component'         => [$clinical . 'SectionComponent', 'section', $clinical . 'Section', false],
            'substance consumable'      => [$clinical . 'SubstanceAdministrationConsumable', 'manufacturedProduct', $clinical . 'ManufacturedProduct', false],
            'supply product'            => [$clinical . 'SupplyProduct', 'manufacturedProduct', $clinical . 'ManufacturedProduct', false],
            'encounter location'        => [$clinical . 'EncompassingEncounterLocation', 'healthCareFacility', $clinical . 'HealthCareFacility', false],
            'encounter responsible'     => [$clinical . 'EncompassingEncounterResponsibleParty', 'assignedEntity', $clinical . 'AssignedEntity', false],
            'observation range'         => [$clinical . 'ObservationReferenceRange', 'observationRange', $clinical . 'ObservationRange', false],
            'observation precondition'  => [$clinical . 'ObservationRangeSdtcPrecondition1', 'criterion1', $clinical . 'Criterion', false],
            'patient relationship'      => [$clinical . 'PersonSdtcAsPatientRelationship', 'code', $datatype . 'CE', false],
            'assigned entity patient'   => [$clinical . 'AssignedEntitySdtcPatient', 'id', $datatype . 'II', false],
            'fulfillment act reference' => [$clinical . 'InFulfillmentOf1ActReference', 'id', $datatype . 'II', true],
        ];
    }

    /**
     * @param class-string $wrapper
     * @param class-string $payloadType
     */
    #[DataProvider('nestedWrapperProvider')]
    public function testNestedWrapperTypesItsPayloadAtARealClass(string $wrapper, string $payload, string $payloadType, bool $repeating): void
    {
        self::assertTrue(class_exists($wrapper), "wrapper class {$wrapper} must be generated");

        $property = new \ReflectionProperty($wrapper, $payload);
        $type     = $property->getType();

        self::assertInstanceOf(\ReflectionNamedType::class, $type, "{$wrapper}::\${$payload} must be typed");

        if (!$repeating) {
            self::assertSame($payloadType, $type->getName());

            return;
        }

        self::assertSame('array', $type->getName());
        self::assertSame('\\' . $payloadType, $this->itemTypeFromMetadata($property), "{$wrapper}::\${$payload} must record its item class");
    }

    /**
     * The item class a repeating property records in its FhirProperty metadata, or null when the
     * metadata is absent — which is itself a failure for a repeating complex property.
     */
    private function itemTypeFromMetadata(\ReflectionProperty $property): ?string
    {
        foreach ($property->getAttributes(FhirProperty::class) as $attribute) {
            $arguments = $attribute->getArguments();
            if (isset($arguments['phpType']) && is_string($arguments['phpType'])) {
                return $arguments['phpType'];
            }
        }

        return null;
    }

    /**
     * Instantiating with no arguments and reading a property is the only check that catches a
     * missing parent::__construct() forward: PHP assigns a promoted parameter's default when the
     * constructor runs, so an unforwarded inherited property is declared but never initialised and
     * throws on access. Static analysis passes either way.
     *
     * @param class-string $wrapper
     * @param class-string $payloadType
     */
    #[DataProvider('nestedWrapperProvider')]
    public function testNestedWrapperInstantiatesAndExposesItsProperties(string $wrapper, string $payload, string $payloadType, bool $repeating): void
    {
        $instance = new $wrapper();

        if ($repeating) {
            self::assertSame([], $instance->{$payload}, "{$wrapper}::\${$payload} must default to an empty list");
        } else {
            self::assertNull($instance->{$payload}, "{$wrapper}::\${$payload} must default to null");
        }

        // Every wrapper except the one CDA types at the FHIR Base type inherits the
        // InfrastructureRoot surface, which is where an unforwarded constructor would blow up.
        if ($instance instanceof InfrastructureRoot) {
            self::assertNull($instance->nullFlavor, "{$wrapper} must initialise its inherited properties");
            self::assertSame([], $instance->realmCode);
        }
    }
}
