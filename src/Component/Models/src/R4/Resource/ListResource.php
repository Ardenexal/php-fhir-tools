<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ListModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ListStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\List\ListEntry;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/List
 *
 * @description A list is a curated collection of resources.
 */
#[FhirResource(type: 'List', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/List', fhirVersion: 'R4')]
class ListResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var ListStatusType|null status current | retired | entered-in-error */
        #[NotBlank]
        public ?ListStatusType $status = null,
        /** @var ListModeType|null mode working | snapshot | changes */
        #[NotBlank]
        public ?ListModeType $mode = null,
        /** @var StringPrimitive|string|null title Descriptive name for the list */
        public StringPrimitive|string|null $title = null,
        /** @var CodeableConcept|null code What the purpose of this list is */
        public ?CodeableConcept $code = null,
        /** @var Reference|null subject If all resources have the same subject */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Context in which list created */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null date When the list was prepared */
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null source Who and/or what defined the list contents (aka Author) */
        public ?Reference $source = null,
        /** @var CodeableConcept|null orderedBy What order the list has */
        public ?CodeableConcept $orderedBy = null,
        /** @var array<Annotation> note Comments about the list */
        public array $note = [],
        /** @var array<ListEntry> entry Entries in the list */
        public array $entry = [],
        /** @var CodeableConcept|null emptyReason Why list is empty */
        public ?CodeableConcept $emptyReason = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
