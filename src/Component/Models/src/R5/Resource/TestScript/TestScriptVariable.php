<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\TestScript;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Variable is set based either on element value in response body or on header field value in the response headers.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.variable', fhirVersion: 'R5')]
class TestScriptVariable extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Descriptive name for this variable */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null defaultValue Default, hard-coded, or user-defined value for this variable */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $defaultValue = null,
        /** @var StringPrimitive|string|null description Natural language description of the variable */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null expression The FHIRPath expression against the fixture body */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null headerField HTTP header field name for source */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $headerField = null,
        /** @var StringPrimitive|string|null hint Hint help text for default value to enter */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $hint = null,
        /** @var StringPrimitive|string|null path XPath or JSONPath against the fixture body */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $path = null,
        /** @var IdPrimitive|null sourceId Fixture Id of source expression or headerField within this variable */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $sourceId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
