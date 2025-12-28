<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRXhtml;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrativeStatusType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Narrative
 *
 * @description A human-readable summary of the resource conveying the essential clinical and business information for the resource.
 */
#[FHIRComplexType(typeName: 'Narrative', fhirVersion: 'R5')]
class FHIRNarrative extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRNarrativeStatusType|null status generated | extensions | additional | empty */
        #[NotBlank]
        public ?FHIRNarrativeStatusType $status = null,
        /** @var FHIRXhtml|null div Limited xhtml content */
        #[NotBlank]
        public ?FHIRXhtml $div = null,
    ) {
        parent::__construct($id, $extension);
    }
}
