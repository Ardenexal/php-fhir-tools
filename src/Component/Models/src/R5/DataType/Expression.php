<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Expression
 *
 * @description A expression that is evaluated in a specified context and returns a value. The context of use of the expression must specify the context in which the expression is evaluated, and how the result of the expression is used.
 */
#[FHIRComplexType(typeName: 'Expression', fhirVersion: 'R5')]
class Expression extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var StringPrimitive|string|null description Natural language description of the condition */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var CodePrimitive|null name Short name assigned to expression for reuse */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $name = null,
        /** @var string|null language text/cql | text/fhirpath | application/x-fhir-query | etc. */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var StringPrimitive|string|null expression Expression in specified language */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $expression = null,
        /** @var UriPrimitive|null reference Where the expression is found */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $reference = null,
    ) {
        parent::__construct($id, $extension);
    }
}
