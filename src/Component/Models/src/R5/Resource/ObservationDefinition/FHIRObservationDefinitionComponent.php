<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Some observations have multiple component observations, expressed as separate code value pairs.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.component', fhirVersion: 'R5')]
class FHIRObservationDefinitionComponent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Type of observation */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
        public array $permittedDataType = [],
        /** @var array<FHIRCoding> permittedUnit Unit for quantitative results */
        public array $permittedUnit = [],
        /** @var array<FHIRObservationDefinitionQualifiedValue> qualifiedValue Set of qualified values for observation results */
        public array $qualifiedValue = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
