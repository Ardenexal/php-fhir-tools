<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductManufactured
 *
 * @description The manufactured item as contained in the packaged medicinal product.
 */
#[FhirResource(
    type: 'MedicinalProductManufactured',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductManufactured',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductManufactured extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null manufacturedDoseForm Dose form as manufactured and before any transformation into the pharmaceutical product */
        #[NotBlank]
        public ?FHIRCodeableConcept $manufacturedDoseForm = null,
        /** @var FHIRCodeableConcept|null unitOfPresentation The “real world” units in which the quantity of the manufactured item is described */
        public ?FHIRCodeableConcept $unitOfPresentation = null,
        /** @var FHIRQuantity|null quantity The quantity or "count number" of the manufactured item */
        #[NotBlank]
        public ?FHIRQuantity $quantity = null,
        /** @var array<FHIRReference> manufacturer Manufacturer of the item (Note that this should be named "manufacturer" but it currently causes technical issues) */
        public array $manufacturer = [],
        /** @var array<FHIRReference> ingredient Ingredient */
        public array $ingredient = [],
        /** @var FHIRProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        public ?FHIRProdCharacteristic $physicalCharacteristics = null,
        /** @var array<FHIRCodeableConcept> otherCharacteristics Other codeable characteristics */
        public array $otherCharacteristics = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
