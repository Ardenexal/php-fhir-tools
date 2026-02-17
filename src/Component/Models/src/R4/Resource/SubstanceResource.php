<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSubstanceStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Substance\SubstanceIngredient;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Substance\SubstanceInstance;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Substance
 *
 * @description A homogeneous material with a definite composition.
 */
#[FhirResource(type: 'Substance', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Substance', fhirVersion: 'R4')]
class SubstanceResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique identifier */
        public array $identifier = [],
        /** @var FHIRSubstanceStatusType|null status active | inactive | entered-in-error */
        public ?FHIRSubstanceStatusType $status = null,
        /** @var array<CodeableConcept> category What class/type of substance this is */
        public array $category = [],
        /** @var CodeableConcept|null code What substance this is */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null description Textual description of the substance, comments */
        public StringPrimitive|string|null $description = null,
        /** @var array<SubstanceInstance> instance If this describes a specific package/container of the substance */
        public array $instance = [],
        /** @var array<SubstanceIngredient> ingredient Composition information about the substance */
        public array $ingredient = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
