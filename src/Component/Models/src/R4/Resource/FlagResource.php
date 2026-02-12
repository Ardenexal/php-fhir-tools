<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FlagStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Flag
 *
 * @description Prospective warnings of potential issues when providing care to the patient.
 */
#[FhirResource(type: 'Flag', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Flag', fhirVersion: 'R4')]
class FlagResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FlagStatusType|null status active | inactive | entered-in-error */
        #[NotBlank]
        public ?FlagStatusType $status = null,
        /** @var array<CodeableConcept> category Clinical, administrative, etc. */
        public array $category = [],
        /** @var CodeableConcept|null code Coded or textual message to display to user */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject Who/What is flag about? */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var Period|null period Time period when flag is active */
        public ?Period $period = null,
        /** @var Reference|null encounter Alert relevant during encounter */
        public ?Reference $encounter = null,
        /** @var Reference|null author Flag creator */
        public ?Reference $author = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
