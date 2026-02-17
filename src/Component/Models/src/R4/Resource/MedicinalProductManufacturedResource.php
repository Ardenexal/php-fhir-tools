<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
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
    fhirVersion: 'R4',
)]
class MedicinalProductManufacturedResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null manufacturedDoseForm Dose form as manufactured and before any transformation into the pharmaceutical product */
        #[NotBlank]
        public ?CodeableConcept $manufacturedDoseForm = null,
        /** @var CodeableConcept|null unitOfPresentation The “real world” units in which the quantity of the manufactured item is described */
        public ?CodeableConcept $unitOfPresentation = null,
        /** @var Quantity|null quantity The quantity or "count number" of the manufactured item */
        #[NotBlank]
        public ?Quantity $quantity = null,
        /** @var array<Reference> manufacturer Manufacturer of the item (Note that this should be named "manufacturer" but it currently causes technical issues) */
        public array $manufacturer = [],
        /** @var array<Reference> ingredient Ingredient */
        public array $ingredient = [],
        /** @var ProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        public ?ProdCharacteristic $physicalCharacteristics = null,
        /** @var array<CodeableConcept> otherCharacteristics Other codeable characteristics */
        public array $otherCharacteristics = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
