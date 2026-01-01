<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRParameterDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Library
 *
 * @description The Library resource is a general-purpose container for knowledge asset definitions. It can be used to describe and expose existing knowledge assets such as logic libraries and information model descriptions, as well as to describe a collection of knowledge assets.
 */
#[FhirResource(type: 'Library', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Library', fhirVersion: 'R4')]
class FHIRLibrary extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this library, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the library */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the library */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this library (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this library (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRString|string|null subtitle Subordinate title of the library */
        public FHIRString|string|null $subtitle = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|null type logic-library | model-definition | asset-collection | module-definition */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX Type of individual the library content is focused on */
        public FHIRCodeableConcept|FHIRReference|null $subjectX = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the library */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for library (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this library is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRString|string|null usage Describes the clinical usage of the library */
        public FHIRString|string|null $usage = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var FHIRDate|null approvalDate When the library was approved by publisher */
        public ?FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the library was last reviewed */
        public ?FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod When the library is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCodeableConcept> topic E.g. Education, Treatment, Assessment, etc. */
        public array $topic = [],
        /** @var array<FHIRContactDetail> author Who authored the content */
        public array $author = [],
        /** @var array<FHIRContactDetail> editor Who edited the content */
        public array $editor = [],
        /** @var array<FHIRContactDetail> reviewer Who reviewed the content */
        public array $reviewer = [],
        /** @var array<FHIRContactDetail> endorser Who endorsed the content */
        public array $endorser = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional documentation, citations, etc. */
        public array $relatedArtifact = [],
        /** @var array<FHIRParameterDefinition> parameter Parameters defined by the library */
        public array $parameter = [],
        /** @var array<FHIRDataRequirement> dataRequirement What data is referenced by this library */
        public array $dataRequirement = [],
        /** @var array<FHIRAttachment> content Contents of the library, either embedded or referenced */
        public array $content = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
