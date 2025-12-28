<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Details of the official nature of this name.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.name.official', fhirVersion: 'R5')]
class FHIRSubstanceSpecificationNameOfficial extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null authority Which authority uses this official name */
        public ?\FHIRCodeableConcept $authority = null,
        /** @var FHIRCodeableConcept|null status The status of the official name */
        public ?\FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null date Date of official name change */
        public ?\FHIRDateTime $date = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
