<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ObservationRangeCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Multiple  ranges of results qualified by different contexts for ordinal or continuous observations conforming to this ObservationDefinition.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.qualifiedInterval', fhirVersion: 'R4')]
class ObservationDefinitionQualifiedInterval extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ObservationRangeCategoryType|null category reference | critical | absolute */
        public ?ObservationRangeCategoryType $category = null,
        /** @var Range|null range The interval itself, for continuous or ordinal observations */
        public ?Range $range = null,
        /** @var CodeableConcept|null context Range context qualifier */
        public ?CodeableConcept $context = null,
        /** @var array<CodeableConcept> appliesTo Targetted population of the range */
        public array $appliesTo = [],
        /** @var AdministrativeGenderType|null gender male | female | other | unknown */
        public ?AdministrativeGenderType $gender = null,
        /** @var Range|null age Applicable age range, if relevant */
        public ?Range $age = null,
        /** @var Range|null gestationalAge Applicable gestational age range, if relevant */
        public ?Range $gestationalAge = null,
        /** @var StringPrimitive|string|null condition Condition associated with the reference range */
        public StringPrimitive|string|null $condition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
