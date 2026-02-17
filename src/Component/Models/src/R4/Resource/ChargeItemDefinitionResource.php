<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItemDefinition\ChargeItemDefinitionApplicability;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItemDefinition\ChargeItemDefinitionPropertyGroup;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItemDefinition
 *
 * @description The ChargeItemDefinition resource provides the properties that apply to the (billing) codes necessary to calculate costs and prices. The properties may differ largely depending on type and realm, therefore this resource gives only a rough structure and requires profiling for each type of billing code system.
 */
#[FhirResource(
    type: 'ChargeItemDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ChargeItemDefinition',
    fhirVersion: 'R4',
)]
class ChargeItemDefinitionResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this charge item definition, represented as a URI (globally unique) */
        #[NotBlank]
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the charge item definition */
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the charge item definition */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null title Name for this charge item definition (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var array<UriPrimitive> derivedFromUri Underlying externally-defined charge item definition */
        public array $derivedFromUri = [],
        /** @var array<CanonicalPrimitive> partOf A larger definition of which this particular definition is a component or step */
        public array $partOf = [],
        /** @var array<CanonicalPrimitive> replaces Completed or terminated request(s) whose function is taken by this new request */
        public array $replaces = [],
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the charge item definition */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for charge item definition (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var DatePrimitive|null approvalDate When the charge item definition was approved by publisher */
        public ?DatePrimitive $approvalDate = null,
        /** @var DatePrimitive|null lastReviewDate When the charge item definition was last reviewed */
        public ?DatePrimitive $lastReviewDate = null,
        /** @var Period|null effectivePeriod When the charge item definition is expected to be used */
        public ?Period $effectivePeriod = null,
        /** @var CodeableConcept|null code Billing codes or product types this definition applies to */
        public ?CodeableConcept $code = null,
        /** @var array<Reference> instance Instances this definition applies to */
        public array $instance = [],
        /** @var array<ChargeItemDefinitionApplicability> applicability Whether or not the billing code is applicable */
        public array $applicability = [],
        /** @var array<ChargeItemDefinitionPropertyGroup> propertyGroup Group of properties which are applicable under the same conditions */
        public array $propertyGroup = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
