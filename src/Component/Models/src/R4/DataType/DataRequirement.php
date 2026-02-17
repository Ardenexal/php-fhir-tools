<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/DataRequirement
 *
 * @description Describes a required data item for evaluation in terms of the type of data, and optional code or date-based filters of the data.
 */
#[FHIRComplexType(typeName: 'DataRequirement', fhirVersion: 'R4')]
class DataRequirement extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRAllTypesType|null type The type of the required data */
        #[NotBlank]
        public ?FHIRAllTypesType $type = null,
        /** @var array<CanonicalPrimitive> profile The profile of the required data */
        public array $profile = [],
        /** @var CodeableConcept|Reference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public CodeableConcept|Reference|null $subjectX = null,
        /** @var array<StringPrimitive|string> mustSupport Indicates specific structure elements that are referenced by the knowledge module */
        public array $mustSupport = [],
        /** @var array<DataRequirementCodeFilter> codeFilter What codes are expected */
        public array $codeFilter = [],
        /** @var array<DataRequirementDateFilter> dateFilter What dates/date ranges are expected */
        public array $dateFilter = [],
        /** @var PositiveIntPrimitive|null limit Number of results */
        public ?PositiveIntPrimitive $limit = null,
        /** @var array<DataRequirementSort> sort Order of the results */
        public array $sort = [],
    ) {
        parent::__construct($id, $extension);
    }
}
