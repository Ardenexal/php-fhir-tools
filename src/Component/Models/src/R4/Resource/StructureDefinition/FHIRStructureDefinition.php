<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRStructureDefinitionKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTypeDerivationRuleType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/StructureDefinition
 *
 * @description A definition of a FHIR structure. This resource is used to describe the underlying resources, data types defined in FHIR, and also for describing extensions and constraints on resources and data types.
 */
#[FhirResource(
    type: 'StructureDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/StructureDefinition',
    fhirVersion: 'R4',
)]
class FHIRStructureDefinition extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this structure definition, represented as a URI (globally unique) */
        #[NotBlank]
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the structure definition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the structure definition */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this structure definition (computer friendly) */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this structure definition (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the structure definition */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for structure definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this structure definition is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var array<FHIRCoding> keyword Assist with indexing and finding */
        public array $keyword = [],
        /** @var FHIRFHIRVersionType|null fhirVersion FHIR Version this StructureDefinition targets */
        public ?FHIRFHIRVersionType $fhirVersion = null,
        /** @var array<FHIRStructureDefinitionMapping> mapping External specification that the content is mapped to */
        public array $mapping = [],
        /** @var FHIRStructureDefinitionKindType|null kind primitive-type | complex-type | resource | logical */
        #[NotBlank]
        public ?FHIRStructureDefinitionKindType $kind = null,
        /** @var FHIRBoolean|null abstract Whether the structure is abstract */
        #[NotBlank]
        public ?FHIRBoolean $abstract = null,
        /** @var array<FHIRStructureDefinitionContext> context If an extension, where it can be used in instances */
        public array $context = [],
        /** @var array<FHIRString|string> contextInvariant FHIRPath invariants - when the extension can be used */
        public array $contextInvariant = [],
        /** @var FHIRUri|null type Type defined or constrained by this structure */
        #[NotBlank]
        public ?FHIRUri $type = null,
        /** @var FHIRCanonical|null baseDefinition Definition that this type is constrained/specialized from */
        public ?FHIRCanonical $baseDefinition = null,
        /** @var FHIRTypeDerivationRuleType|null derivation specialization | constraint - How relates to base definition */
        public ?FHIRTypeDerivationRuleType $derivation = null,
        /** @var FHIRStructureDefinitionSnapshot|null snapshot Snapshot view of the structure */
        public ?FHIRStructureDefinitionSnapshot $snapshot = null,
        /** @var FHIRStructureDefinitionDifferential|null differential Differential view of the structure */
        public ?FHIRStructureDefinitionDifferential $differential = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
