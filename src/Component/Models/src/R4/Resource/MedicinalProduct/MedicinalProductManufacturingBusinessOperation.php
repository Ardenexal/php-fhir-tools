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
 * @description An operation applied to the product, for manufacturing or adminsitrative purpose.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.manufacturingBusinessOperation', fhirVersion: 'R4')]
class MedicinalProductManufacturingBusinessOperation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null operationType The type of manufacturing operation */
        public ?CodeableConcept $operationType = null,
        /** @var Identifier|null authorisationReferenceNumber Regulatory authorization reference number */
        public ?Identifier $authorisationReferenceNumber = null,
        /** @var DateTimePrimitive|null effectiveDate Regulatory authorization date */
        public ?DateTimePrimitive $effectiveDate = null,
        /** @var CodeableConcept|null confidentialityIndicator To indicate if this proces is commercially confidential */
        public ?CodeableConcept $confidentialityIndicator = null,
        /** @var array<Reference> manufacturer The manufacturer or establishment associated with the process */
        public array $manufacturer = [],
        /** @var Reference|null regulator A regulator which oversees the operation */
        public ?Reference $regulator = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
