<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse
 *
 * @description A structured set of questions and their answers. The questions are ordered and grouped into coherent subsets, corresponding to the structure of the grouping of the questionnaire being responded to.
 */
#[FhirResource(
    type: 'QuestionnaireResponse',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/QuestionnaireResponse',
    fhirVersion: 'R4',
)]
class QuestionnaireResponseResource extends DomainResourceResource
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
        /** @var Identifier|null identifier Unique id for this set of answers */
        public ?Identifier $identifier = null,
        /** @var array<Reference> basedOn Request fulfilled by this QuestionnaireResponse */
        public array $basedOn = [],
        /** @var array<Reference> partOf Part of this action */
        public array $partOf = [],
        /** @var CanonicalPrimitive|null questionnaire Form being answered */
        public ?CanonicalPrimitive $questionnaire = null,
        /** @var QuestionnaireResponseStatusType|null status in-progress | completed | amended | entered-in-error | stopped */
        #[NotBlank]
        public ?QuestionnaireResponseStatusType $status = null,
        /** @var Reference|null subject The subject of the questions */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter created as part of */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null authored Date the answers were gathered */
        public ?DateTimePrimitive $authored = null,
        /** @var Reference|null author Person who received and recorded the answers */
        public ?Reference $author = null,
        /** @var Reference|null source The person who answered the questions */
        public ?Reference $source = null,
        /** @var array<QuestionnaireResponseItem> item Groups and questions */
        public array $item = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
