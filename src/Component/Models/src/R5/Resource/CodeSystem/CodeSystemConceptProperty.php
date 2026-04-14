<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\CodeSystem;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A property value for this concept.
 */
#[FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.concept.property', fhirVersion: 'R5')]
class CodeSystemConceptProperty extends BackboneElement
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
        /** @var CodePrimitive|null code Reference to CodeSystem.property.code */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CodePrimitive $code = null,
        /** @var CodePrimitive|Coding|StringPrimitive|string|int|bool|DateTimePrimitive|null value Value of the property for this concept */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                ],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                ],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'valueDecimal'],
            ],
        )]
        #[NotBlank]
        public CodePrimitive|Coding|StringPrimitive|string|int|bool|DateTimePrimitive|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
