<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRObservationRangeCategoryType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description A set of qualified values associated with a context and a set of conditions -  provides a range for quantitative and ordinal observations and a collection of value sets for qualitative observations.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedValue', fhirVersion: 'R5')]
class FHIRObservationDefinitionQualifiedValue extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null context Context qualifier for the set of qualified values */
        public ?FHIRCodeableConcept $context = null,
        /** @var array<FHIRCodeableConcept> appliesTo Targetted population for the set of qualified values */
        public array $appliesTo = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRRange|null age Applicable age range for the set of qualified values */
        public ?FHIRRange $age = null,
        /** @var FHIRRange|null gestationalAge Applicable gestational age range for the set of qualified values */
        public ?FHIRRange $gestationalAge = null,
        /** @var FHIRString|string|null condition Condition associated with the set of qualified values */
        public FHIRString|string|null $condition = null,
        /** @var FHIRObservationRangeCategoryType|null rangeCategory reference | critical | absolute */
        public ?FHIRObservationRangeCategoryType $rangeCategory = null,
        /** @var FHIRRange|null range The range for continuous or ordinal observations */
        public ?FHIRRange $range = null,
        /** @var FHIRCanonical|null validCodedValueSet Value set of valid coded values as part of this set of qualified values */
        public ?FHIRCanonical $validCodedValueSet = null,
        /** @var FHIRCanonical|null normalCodedValueSet Value set of normal coded values as part of this set of qualified values */
        public ?FHIRCanonical $normalCodedValueSet = null,
        /** @var FHIRCanonical|null abnormalCodedValueSet Value set of abnormal coded values as part of this set of qualified values */
        public ?FHIRCanonical $abnormalCodedValueSet = null,
        /** @var FHIRCanonical|null criticalCodedValueSet Value set of critical coded values as part of this set of qualified values */
        public ?FHIRCanonical $criticalCodedValueSet = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
