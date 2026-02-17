<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\NamingSystemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\NamingSystem\NamingSystemUniqueId;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NamingSystem
 *
 * @description A curated namespace that issues unique symbols within that namespace for the identification of concepts, people, devices, etc.  Represents a "System" used within the Identifier and Coding data types.
 */
#[FhirResource(type: 'NamingSystem', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/NamingSystem', fhirVersion: 'R4')]
class NamingSystemResource extends DomainResourceResource
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
        /** @var StringPrimitive|string|null name Name for this naming system (computer friendly) */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var NamingSystemTypeType|null kind codesystem | identifier | root */
        #[NotBlank]
        public ?NamingSystemTypeType $kind = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[NotBlank]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var StringPrimitive|string|null responsible Who maintains system namespace? */
        public StringPrimitive|string|null $responsible = null,
        /** @var CodeableConcept|null type e.g. driver,  provider,  patient, bank etc. */
        public ?CodeableConcept $type = null,
        /** @var MarkdownPrimitive|null description Natural language description of the naming system */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for naming system (if applicable) */
        public array $jurisdiction = [],
        /** @var StringPrimitive|string|null usage How/where is it used */
        public StringPrimitive|string|null $usage = null,
        /** @var array<NamingSystemUniqueId> uniqueId Unique identifiers used for system */
        public array $uniqueId = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
