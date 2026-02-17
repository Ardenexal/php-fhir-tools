<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GroupTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Group\GroupCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Group\GroupMember;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Group
 *
 * @description Represents a defined collection of entities that may be discussed or acted upon collectively but which are not expected to act collectively, and are not formally or legally recognized; i.e. a collection of entities that isn't an Organization.
 */
#[FhirResource(type: 'Group', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Group', fhirVersion: 'R4')]
class GroupResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Unique id */
        public array $identifier = [],
        /** @var bool|null active Whether this group's record is in active use */
        public ?bool $active = null,
        /** @var GroupTypeType|null type person | animal | practitioner | device | medication | substance */
        #[NotBlank]
        public ?GroupTypeType $type = null,
        /** @var bool|null actual Descriptive or actual */
        #[NotBlank]
        public ?bool $actual = null,
        /** @var CodeableConcept|null code Kind of Group members */
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null name Label for Group */
        public StringPrimitive|string|null $name = null,
        /** @var UnsignedIntPrimitive|null quantity Number of members */
        public ?UnsignedIntPrimitive $quantity = null,
        /** @var Reference|null managingEntity Entity that is the custodian of the Group's definition */
        public ?Reference $managingEntity = null,
        /** @var array<GroupCharacteristic> characteristic Include / Exclude group members by Trait */
        public array $characteristic = [],
        /** @var array<GroupMember> member Who or what is in group */
        public array $member = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
