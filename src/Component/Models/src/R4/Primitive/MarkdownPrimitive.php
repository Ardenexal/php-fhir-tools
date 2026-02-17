<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/markdown
 *
 * @description A string that may contain Github Flavored Markdown syntax for optional processing by a mark down presentation engine
 */
#[FHIRPrimitive(primitiveType: 'markdown', fhirVersion: 'R4')]
class MarkdownPrimitive extends StringPrimitive
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var string|null value Primitive value for markdown */
        public ?string $value = null,
    ) {
        parent::__construct($id, $extension, $value);
    }
}
