<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/DataRequirement
 *
 * @description Describes a required data item for evaluation in terms of the type of data, and optional code or date-based filters of the data.
 */
#[FHIRComplexType(typeName: 'DataRequirement', fhirVersion: 'R4B')]
class FHIRDataRequirement extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRFHIRAllTypesType|null type The type of the required data */
        #[NotBlank]
        public ?FHIRFHIRAllTypesType $type = null,
        /** @var array<FHIRCanonical> profile The profile of the required data */
        public array $profile = [],
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX E.g. Patient, Practitioner, RelatedPerson, Organization, Location, Device */
        public FHIRCodeableConcept|FHIRReference|null $subjectX = null,
        /** @var array<FHIRString|string> mustSupport Indicates specific structure elements that are referenced by the knowledge module */
        public array $mustSupport = [],
        /** @var array<FHIRDataRequirementCodeFilter> codeFilter What codes are expected */
        public array $codeFilter = [],
        /** @var array<FHIRDataRequirementDateFilter> dateFilter What dates/date ranges are expected */
        public array $dateFilter = [],
        /** @var FHIRPositiveInt|null limit Number of results */
        public ?FHIRPositiveInt $limit = null,
        /** @var array<FHIRDataRequirementSort> sort Order of the results */
        public array $sort = [],
    ) {
        parent::__construct($id, $extension);
    }
}
