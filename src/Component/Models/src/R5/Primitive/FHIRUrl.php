<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/url
 *
 * @description A URI that is a literal reference
 */
#[FHIRPrimitive(primitiveType: 'url', fhirVersion: 'R5')]
class FHIRUrl extends FHIRUri
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for url */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension, $value);
    }
}
