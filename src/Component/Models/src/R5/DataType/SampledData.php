<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 *
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R5')]
class SampledData extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Quantity|null origin Zero value and units */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Quantity $origin = null,
        /** @var numeric-string|null interval Number of intervalUnits between samples */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $interval = null,
        /** @var UCUMCodesType|null intervalUnit The measurement unit of the interval between samples */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UCUMCodesType $intervalUnit = null,
        /** @var numeric-string|null factor Multiply data by this before adding to origin */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $factor = null,
        /** @var numeric-string|null lowerLimit Lower limit of detection */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $lowerLimit = null,
        /** @var numeric-string|null upperLimit Upper limit of detection */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $upperLimit = null,
        /** @var PositiveIntPrimitive|null dimensions Number of sample points at each time point */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PositiveIntPrimitive $dimensions = null,
        /** @var CanonicalPrimitive|null codeMap Defines the codes used in the data */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $codeMap = null,
        /** @var StringPrimitive|string|null offsets Offsets, typically in time, at which data values were taken */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $offsets = null,
        /** @var StringPrimitive|string|null data Decimal values with spaces, or "E" | "U" | "L", or another code */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
