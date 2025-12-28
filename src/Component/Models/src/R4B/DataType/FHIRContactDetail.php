<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ContactDetail
 *
 * @description Specifies contact information for a person or organization.
 */
#[FHIRComplexType(typeName: 'ContactDetail', fhirVersion: 'R4B')]
class FHIRContactDetail extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null name Name of an individual to contact */
        public \FHIRString|string|null $name = null,
        /** @var array<FHIRContactPoint> telecom Contact details for individual or organization */
        public array $telecom = [],
    ) {
        parent::__construct($id, $extension);
    }
}
