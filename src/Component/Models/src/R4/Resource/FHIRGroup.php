<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRGroupTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Group
 *
 * @description Represents a defined collection of entities that may be discussed or acted upon collectively but which are not expected to act collectively, and are not formally or legally recognized; i.e. a collection of entities that isn't an Organization.
 */
#[FhirResource(type: 'Group', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Group', fhirVersion: 'R4')]
class FHIRGroup extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Unique id */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this group's record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var FHIRGroupTypeType|null type person | animal | practitioner | device | medication | substance */
        #[NotBlank]
        public ?FHIRGroupTypeType $type = null,
        /** @var FHIRBoolean|null actual Descriptive or actual */
        #[NotBlank]
        public ?FHIRBoolean $actual = null,
        /** @var FHIRCodeableConcept|null code Kind of Group members */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null name Label for Group */
        public FHIRString|string|null $name = null,
        /** @var FHIRUnsignedInt|null quantity Number of members */
        public ?FHIRUnsignedInt $quantity = null,
        /** @var FHIRReference|null managingEntity Entity that is the custodian of the Group's definition */
        public ?FHIRReference $managingEntity = null,
        /** @var array<FHIRGroupCharacteristic> characteristic Include / Exclude group members by Trait */
        public array $characteristic = [],
        /** @var array<FHIRGroupMember> member Who or what is in group */
        public array $member = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
