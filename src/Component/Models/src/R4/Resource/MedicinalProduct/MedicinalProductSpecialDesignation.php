<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description Indicates if the medicinal product has an orphan designation for the treatment of a rare disease.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.specialDesignation', fhirVersion: 'R4')]
class MedicinalProductSpecialDesignation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Identifier for the designation, or procedure number */
        public array $identifier = [],
        /** @var CodeableConcept|null type The type of special designation, e.g. orphan drug, minor use */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null intendedUse The intended use of the product, e.g. prevention, treatment */
        public ?CodeableConcept $intendedUse = null,
        /** @var CodeableConcept|Reference|null indicationX Condition for which the medicinal use applies */
        public CodeableConcept|Reference|null $indicationX = null,
        /** @var CodeableConcept|null status For example granted, pending, expired or withdrawn */
        public ?CodeableConcept $status = null,
        /** @var DateTimePrimitive|null date Date when the designation was granted */
        public ?DateTimePrimitive $date = null,
        /** @var CodeableConcept|null species Animal species for which this applies */
        public ?CodeableConcept $species = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
