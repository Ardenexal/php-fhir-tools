<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Expression
 *
 * @description A expression that is evaluated in a specified context and returns a value. The context of use of the expression must specify the context in which the expression is evaluated, and how the result of the expression is used.
 */
#[FHIRComplexType(typeName: 'Expression', fhirVersion: 'R4B')]
class FHIRExpression extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null description Natural language description of the condition */
        public \FHIRString|string|null $description = null,
        /** @var FHIRId|null name Short name assigned to expression for reuse */
        public ?\FHIRId $name = null,
        /** @var string|null language text/cql | text/fhirpath | application/x-fhir-query | text/cql-identifier | text/cql-expression | etc. */
        #[NotBlank]
        public ?string $language = null,
        /** @var FHIRString|string|null expression Expression in specified language */
        public \FHIRString|string|null $expression = null,
        /** @var FHIRUri|null reference Where the expression is found */
        public ?\FHIRUri $reference = null,
    ) {
        parent::__construct($id, $extension);
    }
}
