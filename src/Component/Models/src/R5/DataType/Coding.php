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
 * @see http://hl7.org/fhir/StructureDefinition/Coding
 *
 * @description A reference to a code defined by a terminology system.
 */
#[FHIRComplexType(typeName: 'Coding', fhirVersion: 'R5')]
class Coding extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var UriPrimitive|null system Identity of the terminology system */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $system = null,
        /** @var StringPrimitive|string|null version Version of the system - if relevant */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var CodePrimitive|null code Symbol in syntax defined by the system */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null display Representation defined by the system */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $display = null,
        /** @var bool|null userSelected If this coding was chosen directly by the user */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $userSelected = null,
    ) {
        parent::__construct($id, $extension);
    }
}
