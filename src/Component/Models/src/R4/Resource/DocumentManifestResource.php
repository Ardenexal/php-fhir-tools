<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DocumentReferenceStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentManifest\DocumentManifestRelated;
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
class DocumentManifestResource extends DomainResourceResource
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
        /** @var Identifier|null masterIdentifier Unique Identifier for the set of documents */
        public ?Identifier $masterIdentifier = null,
        /** @var array<Identifier> identifier Other identifiers for the manifest */
        public array $identifier = [],
        /** @var DocumentReferenceStatusType|null status current | superseded | entered-in-error */
        #[NotBlank]
        public ?DocumentReferenceStatusType $status = null,
        /** @var CodeableConcept|null type Kind of document set */
        public ?CodeableConcept $type = null,
        /** @var Reference|null subject The subject of the set of documents */
        public ?Reference $subject = null,
        /** @var DateTimePrimitive|null created When this document manifest created */
        public ?DateTimePrimitive $created = null,
        /** @var array<Reference> author Who and/or what authored the DocumentManifest */
        public array $author = [],
        /** @var array<Reference> recipient Intended to get notified about this set of documents */
        public array $recipient = [],
        /** @var UriPrimitive|null source The source system/application/software */
        public ?UriPrimitive $source = null,
        /** @var StringPrimitive|string|null description Human-readable description (title) */
        public StringPrimitive|string|null $description = null,
        /** @var array<Reference> content Items in manifest */
        public array $content = [],
        /** @var array<DocumentManifestRelated> related Related things */
        public array $related = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
