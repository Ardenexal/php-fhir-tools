<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Reference
 *
 * @description A reference from one resource to another.
 */
#[FHIRComplexType(typeName: 'Reference', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'ref-1',
    severity: 'error',
    expression: 'reference.startsWith(\'#\').not() or (reference.substring(1).trace(\'url\') in %rootResource.contained.id.trace(\'ids\')) or (reference=\'#\' and %rootResource!=%resource)',
    human: 'SHALL have a contained resource if a local reference is provided',
)]
class Reference extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var StringPrimitive|string|null reference Literal reference, Relative, internal or absolute URL */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $reference = null,
        /** @var UriPrimitive|null type Type the reference refers to (e.g. "Patient") */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/resource-types', strength: 'extensible')]
        public ?UriPrimitive $type = null,
        /** @var Identifier|null identifier Logical reference, when literal reference is not known */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var StringPrimitive|string|null display Text alternative for the resource */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $display = null,
    ) {
        parent::__construct($id, $extension);
    }
}
