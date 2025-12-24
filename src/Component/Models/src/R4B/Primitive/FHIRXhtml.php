<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/xhtml
 *
 * @description XHTML
 */
#[FHIRPrimitive(primitiveType: 'xhtml', fhirVersion: 'R4B')]
class FHIRXhtml extends FHIRElement
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var string|null value Actual xhtml */
        #[NotBlank]
        public ?string $value = null,
    ) {
        parent::__construct($id);
    }
}
