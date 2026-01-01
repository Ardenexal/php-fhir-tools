<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;

/**
 * @description An operation applied to the product, for manufacturing or adminsitrative purpose.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.manufacturingBusinessOperation', fhirVersion: 'R4')]
class FHIRMedicinalProductManufacturingBusinessOperation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null operationType The type of manufacturing operation */
        public ?FHIRCodeableConcept $operationType = null,
        /** @var FHIRIdentifier|null authorisationReferenceNumber Regulatory authorization reference number */
        public ?FHIRIdentifier $authorisationReferenceNumber = null,
        /** @var FHIRDateTime|null effectiveDate Regulatory authorization date */
        public ?FHIRDateTime $effectiveDate = null,
        /** @var FHIRCodeableConcept|null confidentialityIndicator To indicate if this proces is commercially confidential */
        public ?FHIRCodeableConcept $confidentialityIndicator = null,
        /** @var array<FHIRReference> manufacturer The manufacturer or establishment associated with the process */
        public array $manufacturer = [],
        /** @var FHIRReference|null regulator A regulator which oversees the operation */
        public ?FHIRReference $regulator = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
