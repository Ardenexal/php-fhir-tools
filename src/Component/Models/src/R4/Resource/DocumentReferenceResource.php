<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CompositionStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentReferenceStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference\DocumentReferenceContent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference\DocumentReferenceContext;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentReference\DocumentReferenceRelatesTo;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/DocumentReference',
    fhirVersion: 'R4',
)]
class DocumentReferenceResource extends DomainResourceResource
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
        /** @var Identifier|null masterIdentifier Master Version Specific Identifier */
        public ?Identifier $masterIdentifier = null,
        /** @var array<Identifier> identifier Other identifiers for the document */
        public array $identifier = [],
        /** @var DocumentReferenceStatusType|null status current | superseded | entered-in-error */
        #[NotBlank]
        public ?DocumentReferenceStatusType $status = null,
        /** @var CompositionStatusType|null docStatus preliminary | final | amended | entered-in-error */
        public ?CompositionStatusType $docStatus = null,
        /** @var CodeableConcept|null type Kind of document (LOINC if possible) */
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> category Categorization of document */
        public array $category = [],
        /** @var Reference|null subject Who/what is the subject of the document */
        public ?Reference $subject = null,
        /** @var InstantPrimitive|null date When this document reference was created */
        public ?InstantPrimitive $date = null,
        /** @var array<Reference> author Who and/or what authored the document */
        public array $author = [],
        /** @var Reference|null authenticator Who/what authenticated the document */
        public ?Reference $authenticator = null,
        /** @var Reference|null custodian Organization which maintains the document */
        public ?Reference $custodian = null,
        /** @var array<DocumentReferenceRelatesTo> relatesTo Relationships to other documents */
        public array $relatesTo = [],
        /** @var StringPrimitive|string|null description Human-readable description */
        public StringPrimitive|string|null $description = null,
        /** @var array<CodeableConcept> securityLabel Document security-tags */
        public array $securityLabel = [],
        /** @var array<DocumentReferenceContent> content Document referenced */
        public array $content = [],
        /** @var DocumentReferenceContext|null context Clinical context of document */
        public ?DocumentReferenceContext $context = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
