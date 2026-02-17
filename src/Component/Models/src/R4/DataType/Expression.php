<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Expression
 *
 * @description A expression that is evaluated in a specified context and returns a value. The context of use of the expression must specify the context in which the expression is evaluated, and how the result of the expression is used.
 */
#[FHIRComplexType(typeName: 'Expression', fhirVersion: 'R4')]
class Expression extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var StringPrimitive|string|null description Natural language description of the condition */
        public StringPrimitive|string|null $description = null,
        /** @var IdPrimitive|null name Short name assigned to expression for reuse */
        public ?IdPrimitive $name = null,
        /** @var string|null language text/cql | text/fhirpath | application/x-fhir-query | etc. */
        #[NotBlank]
        public ?string $language = null,
        /** @var StringPrimitive|string|null expression Expression in specified language */
        public StringPrimitive|string|null $expression = null,
        /** @var UriPrimitive|null reference Where the expression is found */
        public ?UriPrimitive $reference = null,
    ) {
        parent::__construct($id, $extension);
    }
}
