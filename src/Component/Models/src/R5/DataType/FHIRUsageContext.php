<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/UsageContext
 *
 * @description Specifies clinical/business/etc. metadata that can be used to retrieve, index and/or categorize an artifact. This metadata can either be specific to the applicable population (e.g., age category, DRG) or the specific context of care (e.g., venue, care setting, provider of care).
 */
#[FHIRComplexType(typeName: 'UsageContext', fhirVersion: 'R5')]
class FHIRUsageContext extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRCoding|null code Type of context being specified */
        #[NotBlank]
        public ?FHIRCoding $code = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRRange|FHIRReference|null valueX Value that defines the context */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRQuantity|FHIRRange|FHIRReference|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
