<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/DataType
 *
 * @description The base class for all re-useable types defined as part of the FHIR Specification.
 */
#[FHIRComplexType(typeName: 'DataType', fhirVersion: 'R4B')]
abstract class FHIRDataType extends FHIRElement
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
