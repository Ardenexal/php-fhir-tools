<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @description Indicates if the medicinal product has an orphan designation for the treatment of a rare disease.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.specialDesignation', fhirVersion: 'R5')]
class FHIRMedicinalProductSpecialDesignation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifier for the designation, or procedure number */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type The type of special designation, e.g. orphan drug, minor use */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null intendedUse The intended use of the product, e.g. prevention, treatment */
        public ?FHIRCodeableConcept $intendedUse = null,
        /** @var FHIRCodeableConcept|FHIRReference|null indicationX Condition for which the medicinal use applies */
        public FHIRCodeableConcept|FHIRReference|null $indicationX = null,
        /** @var FHIRCodeableConcept|null status For example granted, pending, expired or withdrawn */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null date Date when the designation was granted */
        public ?FHIRDateTime $date = null,
        /** @var FHIRCodeableConcept|null species Animal species for which this applies */
        public ?FHIRCodeableConcept $species = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
