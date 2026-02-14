<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\XhtmlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Narrative
 *
 * @description A human-readable summary of the resource conveying the essential clinical and business information for the resource.
 */
#[FHIRComplexType(typeName: 'Narrative', fhirVersion: 'R4')]
class Narrative extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var NarrativeStatusType|null status generated | extensions | additional | empty */
        #[NotBlank]
        public ?NarrativeStatusType $status = null,
        /** @var XhtmlPrimitive|null div Limited xhtml content */
        #[NotBlank]
        public ?XhtmlPrimitive $div = null,
    ) {
        parent::__construct($id, $extension);
    }
}
