<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/List
 *
 * @description A List is a curated collection of resources, for things such as problem lists, allergy lists, facility list, organization list, etc.
 */
#[FhirResource(type: 'List', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/List', fhirVersion: 'R5')]
class FHIRList extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRListStatusType|null status current | retired | entered-in-error */
        #[NotBlank]
        public ?FHIRListStatusType $status = null,
        /** @var FHIRListModeType|null mode working | snapshot | changes */
        #[NotBlank]
        public ?FHIRListModeType $mode = null,
        /** @var FHIRString|string|null title Descriptive name for the list */
        public FHIRString|string|null $title = null,
        /** @var FHIRCodeableConcept|null code What the purpose of this list is */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRReference> subject If all resources have the same subject(s) */
        public array $subject = [],
        /** @var FHIRReference|null encounter Context in which list created */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null date When the list was prepared */
        public ?FHIRDateTime $date = null,
        /** @var FHIRReference|null source Who and/or what defined the list contents (aka Author) */
        public ?FHIRReference $source = null,
        /** @var FHIRCodeableConcept|null orderedBy What order the list has */
        public ?FHIRCodeableConcept $orderedBy = null,
        /** @var array<FHIRAnnotation> note Comments about the list */
        public array $note = [],
        /** @var array<FHIRListEntry> entry Entries in the list */
        public array $entry = [],
        /** @var FHIRCodeableConcept|null emptyReason Why list is empty */
        public ?FHIRCodeableConcept $emptyReason = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
