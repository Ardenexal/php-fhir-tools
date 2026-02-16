<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description Details of the official nature of this name.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.name.official', fhirVersion: 'R4')]
class SubstanceSpecificationNameOfficial extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null authority Which authority uses this official name */
        public ?CodeableConcept $authority = null,
        /** @var CodeableConcept|null status The status of the official name */
        public ?CodeableConcept $status = null,
        /** @var DateTimePrimitive|null date Date of official name change */
        public ?DateTimePrimitive $date = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
