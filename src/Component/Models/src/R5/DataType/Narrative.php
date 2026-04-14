<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\XhtmlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Narrative
 *
 * @description A human-readable summary of the resource conveying the essential clinical and business information for the resource.
 */
#[FHIRComplexType(typeName: 'Narrative', fhirVersion: 'R5')]
class Narrative extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var NarrativeStatusType|null status generated | extensions | additional | empty */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?NarrativeStatusType $status = null,
        /** @var XhtmlPrimitive|null div Limited xhtml content */
        #[FhirProperty(fhirType: 'xhtml', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?XhtmlPrimitive $div = null,
    ) {
        parent::__construct($id, $extension);
    }
}
