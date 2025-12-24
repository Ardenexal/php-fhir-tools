<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/PrimitiveType
 *
 * @description The base type for all re-useable types defined that have a simple property.
 */
#[FHIRComplexType(typeName: 'PrimitiveType', fhirVersion: 'R5')]
abstract class FHIRPrimitiveType extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
    ) {
        parent::__construct($id, $extension);
    }
}
