<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Structured Documents)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DocumentReference
 *
 * @description A reference to a document of any kind for any purpose. Provides metadata about the document so that the document can be discovered and managed. The scope of a document is any seralized object with a mime-type, so includes formal patient centric documents (CDA), cliical notes, scanned paper, and non-patient specific documents like policy text.
 */
#[FhirResource(
    type: 'DocumentReference',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DocumentReference',
    fhirVersion: 'R4B',
)]
class FHIRDocumentReference extends FHIRDomainResource
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
        /** @var FHIRIdentifier|null masterIdentifier Master Version Specific Identifier */
        public ?FHIRIdentifier $masterIdentifier = null,
        /** @var array<FHIRIdentifier> identifier Other identifiers for the document */
        public array $identifier = [],
        /** @var FHIRDocumentReferenceStatusType|null status current | superseded | entered-in-error */
        #[NotBlank]
        public ?FHIRDocumentReferenceStatusType $status = null,
        /** @var FHIRCompositionStatusType|null docStatus preliminary | final | amended | entered-in-error */
        public ?FHIRCompositionStatusType $docStatus = null,
        /** @var FHIRCodeableConcept|null type Kind of document (LOINC if possible) */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> category Categorization of document */
        public array $category = [],
        /** @var FHIRReference|null subject Who/what is the subject of the document */
        public ?FHIRReference $subject = null,
        /** @var FHIRInstant|null date When this document reference was created */
        public ?FHIRInstant $date = null,
        /** @var array<FHIRReference> author Who and/or what authored the document */
        public array $author = [],
        /** @var FHIRReference|null authenticator Who/what authenticated the document */
        public ?FHIRReference $authenticator = null,
        /** @var FHIRReference|null custodian Organization which maintains the document */
        public ?FHIRReference $custodian = null,
        /** @var array<FHIRDocumentReferenceRelatesTo> relatesTo Relationships to other documents */
        public array $relatesTo = [],
        /** @var FHIRString|string|null description Human-readable description */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRCodeableConcept> securityLabel Document security-tags */
        public array $securityLabel = [],
        /** @var array<FHIRDocumentReferenceContent> content Document referenced */
        public array $content = [],
        /** @var FHIRDocumentReferenceContext|null context Clinical context of document */
        public ?FHIRDocumentReferenceContext $context = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
