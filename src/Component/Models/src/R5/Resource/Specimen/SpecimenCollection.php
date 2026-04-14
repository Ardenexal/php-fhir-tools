<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Specimen;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;

/**
 * @description Details concerning the specimen collection.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.collection', fhirVersion: 'R5')]
class SpecimenCollection extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Reference|null collector Who collected the specimen */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $collector = null,
        /** @var DateTimePrimitive|Period|null collected Collection time */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'collectedDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'collectedPeriod',
                ],
            ],
        )]
        public DateTimePrimitive|Period|null $collected = null,
        /** @var Duration|null duration How long it took to collect specimen */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $duration = null,
        /** @var Quantity|null quantity The quantity of specimen collected */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var CodeableConcept|null method Technique used to perform collection */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $method = null,
        /** @var CodeableReference|null device Device used to perform collection */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $device = null,
        /** @var Reference|null procedure The procedure that collects the specimen */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $procedure = null,
        /** @var CodeableReference|null bodySite Anatomical collection site */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $bodySite = null,
        /** @var CodeableConcept|Duration|null fastingStatus Whether or how long patient abstained from food and/or drink */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'fastingStatusCodeableConcept',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
                    'jsonKey'      => 'fastingStatusDuration',
                ],
            ],
        )]
        public CodeableConcept|Duration|null $fastingStatus = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
