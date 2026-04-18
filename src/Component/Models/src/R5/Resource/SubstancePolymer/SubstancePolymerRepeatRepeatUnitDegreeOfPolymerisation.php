<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @description Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstancePolymer',
    elementPath: 'SubstancePolymer.repeat.repeatUnit.degreeOfPolymerisation',
    fhirVersion: 'R5',
)]
class SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation extends BackboneElement
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
        /** @var CodeableConcept|null type The type of the degree of polymerisation shall be described, e.g. SRU/Polymer Ratio */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var int|null average An average amount of polymerisation */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $average = null,
        /** @var int|null low A low expected limit of the amount */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $low = null,
        /** @var int|null high A high expected limit of the amount */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $high = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
