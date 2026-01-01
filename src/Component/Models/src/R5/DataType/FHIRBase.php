<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Base
 *
 * @description Base definition for all types defined in FHIR type system.
 */
#[FHIRComplexType(typeName: 'Base', fhirVersion: 'R5')]
abstract class FHIRBase
{
    public function __construct()
    {
    }
}
