<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Questionnaire
 *
 * @description A structured set of questions intended to guide the collection of answers from end-users. Questionnaires provide detailed control over order, presentation, phraseology and grouping to allow coherent, consistent data collection.
 */
#[FhirResource(type: 'Questionnaire', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Questionnaire', fhirVersion: 'R4')]
class QuestionnaireResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this questionnaire, represented as a URI (globally unique) */
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the questionnaire */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the questionnaire */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this questionnaire (computer friendly) */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this questionnaire (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var array<CanonicalPrimitive> derivedFrom Instantiates protocol or definition */
        public array $derivedFrom = [],
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var array<ResourceTypeType> subjectType Resource that can be subject of QuestionnaireResponse */
        public array $subjectType = [],
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the questionnaire */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for questionnaire (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this questionnaire is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var DatePrimitive|null approvalDate When the questionnaire was approved by publisher */
        public ?DatePrimitive $approvalDate = null,
        /** @var DatePrimitive|null lastReviewDate When the questionnaire was last reviewed */
        public ?DatePrimitive $lastReviewDate = null,
        /** @var Period|null effectivePeriod When the questionnaire is expected to be used */
        public ?Period $effectivePeriod = null,
        /** @var array<Coding> code Concept that represents the overall questionnaire */
        public array $code = [],
        /** @var array<QuestionnaireItem> item Questions and sections within the Questionnaire */
        public array $item = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
