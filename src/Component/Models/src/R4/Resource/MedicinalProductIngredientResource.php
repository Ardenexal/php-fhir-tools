<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient\MedicinalProductIngredientSpecifiedSubstance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIngredient\MedicinalProductIngredientSubstance;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductIngredient
 *
 * @description An ingredient of a manufactured item or pharmaceutical product.
 */
#[FhirResource(
    type: 'MedicinalProductIngredient',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductIngredient',
    fhirVersion: 'R4',
)]
class MedicinalProductIngredientResource extends DomainResourceResource
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
        /** @var Identifier|null identifier Identifier for the ingredient */
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null role Ingredient role e.g. Active ingredient, excipient */
        #[NotBlank]
        public ?CodeableConcept $role = null,
        /** @var bool|null allergenicIndicator If the ingredient is a known or suspected allergen */
        public ?bool $allergenicIndicator = null,
        /** @var array<Reference> manufacturer Manufacturer of this Ingredient */
        public array $manufacturer = [],
        /** @var array<MedicinalProductIngredientSpecifiedSubstance> specifiedSubstance A specified substance that comprises this ingredient */
        public array $specifiedSubstance = [],
        /** @var MedicinalProductIngredientSubstance|null substance The ingredient substance */
        public ?MedicinalProductIngredientSubstance $substance = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
