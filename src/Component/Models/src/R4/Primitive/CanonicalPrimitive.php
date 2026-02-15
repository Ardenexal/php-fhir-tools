<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/canonical
 *
 * @description A URI that is a reference to a canonical URL on a FHIR resource
 */
#[FHIRPrimitive(primitiveType: 'canonical', fhirVersion: 'R4')]
class CanonicalPrimitive extends UriPrimitive
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for canonical */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension, $value);
    }
}
