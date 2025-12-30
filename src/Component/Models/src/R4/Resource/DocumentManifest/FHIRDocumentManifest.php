<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDocumentReferenceStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Structured Documents)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DocumentManifest
 *
 * @description A collection of documents compiled for a purpose together with metadata that applies to the collection.
 */
#[FhirResource(
    type: 'DocumentManifest',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/DocumentManifest',
    fhirVersion: 'R4',
)]
class FHIRDocumentManifest extends FHIRDomainResource
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
        /** @var FHIRIdentifier|null masterIdentifier Unique Identifier for the set of documents */
        public ?FHIRIdentifier $masterIdentifier = null,
        /** @var array<FHIRIdentifier> identifier Other identifiers for the manifest */
        public array $identifier = [],
        /** @var FHIRDocumentReferenceStatusType|null status current | superseded | entered-in-error */
        #[NotBlank]
        public ?FHIRDocumentReferenceStatusType $status = null,
        /** @var FHIRCodeableConcept|null type Kind of document set */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null subject The subject of the set of documents */
        public ?FHIRReference $subject = null,
        /** @var FHIRDateTime|null created When this document manifest created */
        public ?FHIRDateTime $created = null,
        /** @var array<FHIRReference> author Who and/or what authored the DocumentManifest */
        public array $author = [],
        /** @var array<FHIRReference> recipient Intended to get notified about this set of documents */
        public array $recipient = [],
        /** @var FHIRUri|null source The source system/application/software */
        public ?FHIRUri $source = null,
        /** @var FHIRString|string|null description Human-readable description (title) */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRReference> content Items in manifest */
        public array $content = [],
        /** @var array<FHIRDocumentManifestRelated> related Related things */
        public array $related = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
