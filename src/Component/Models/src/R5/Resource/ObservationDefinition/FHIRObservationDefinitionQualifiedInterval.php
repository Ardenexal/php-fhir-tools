<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRObservationRangeCategoryType;

/**
 * @description Multiple  ranges of results qualified by different contexts for ordinal or continuous observations conforming to this ObservationDefinition.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedInterval', fhirVersion: 'R4B')]
class FHIRObservationDefinitionQualifiedInterval extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRObservationRangeCategoryType|null category reference | critical | absolute */
        public ?FHIRObservationRangeCategoryType $category = null,
        /** @var FHIRRange|null range The interval itself, for continuous or ordinal observations */
        public ?FHIRRange $range = null,
        /** @var FHIRCodeableConcept|null context Range context qualifier */
        public ?FHIRCodeableConcept $context = null,
        /** @var array<FHIRCodeableConcept> appliesTo Targetted population of the range */
        public array $appliesTo = [],
        /** @var FHIRAdministrativeGenderType|null gender male | female | other | unknown */
        public ?FHIRAdministrativeGenderType $gender = null,
        /** @var FHIRRange|null age Applicable age range, if relevant */
        public ?FHIRRange $age = null,
        /** @var FHIRRange|null gestationalAge Applicable gestational age range, if relevant */
        public ?FHIRRange $gestationalAge = null,
        /** @var FHIRString|string|null condition Condition associated with the reference range */
        public FHIRString|string|null $condition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
