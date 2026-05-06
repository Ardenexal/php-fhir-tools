<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Annotation
 *
 * @description A  text note which also  contains information about who made the statement and when.
 */
#[FHIRComplexType(typeName: 'Annotation', fhirVersion: 'R5')]
class Annotation extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Reference|StringPrimitive|string|null author Individual responsible for the annotation */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'authorReference',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'authorString',
                ],
            ],
        )]
        public Reference|StringPrimitive|string|null $author = null,
        /** @var DateTimePrimitive|null time When the annotation was made */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $time = null,
        /** @var MarkdownPrimitive|null text The annotation  - text content (as markdown) */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?MarkdownPrimitive $text = null,
    ) {
        parent::__construct($id, $extension);
    }
}
