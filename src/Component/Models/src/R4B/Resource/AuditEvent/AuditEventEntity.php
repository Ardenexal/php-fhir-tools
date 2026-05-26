<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\AuditEvent;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @description Specific instances of data or objects that have been accessed.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'sev-1',
    severity: 'error',
    expression: 'name.empty() or query.empty()',
    human: 'Either a name or a query (NOT both)',
)]
class AuditEventEntity extends BackboneElement
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
        /** @var Reference|null what Specific instance of resource */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $what = null,
        /** @var Coding|null type Type of entity involved */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/audit-entity-type', strength: 'extensible')]
        public ?Coding $type = null,
        /** @var Coding|null role What role the entity played */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/object-role', strength: 'extensible')]
        public ?Coding $role = null,
        /** @var Coding|null lifecycle Life-cycle stage for the entity */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/object-lifecycle-events', strength: 'extensible')]
        public ?Coding $lifecycle = null,
        /** @var array<Coding> securityLabel Security labels on the entity */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/security-labels', strength: 'extensible')]
        public array $securityLabel = [],
        /** @var StringPrimitive|string|null name Descriptor for entity */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Descriptive text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var Base64BinaryPrimitive|null query Query parameters */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public ?Base64BinaryPrimitive $query = null,
        /** @var array<AuditEventEntityDetail> detail Additional Information about the entity */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\AuditEvent\AuditEventEntityDetail',
        )]
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
