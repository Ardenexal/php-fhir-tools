<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Provenance;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ProvenanceEntityRoleType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An entity used in this activity.
 */
#[FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.entity', fhirVersion: 'R4B')]
class ProvenanceEntity extends BackboneElement
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
        /** @var ProvenanceEntityRoleType|null role derivation | revision | quotation | source | removal */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ProvenanceEntityRoleType $role = null,
        /** @var Reference|null what Identity of entity */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $what = null,
        /** @var array<ProvenanceAgent> agent Entity is attributed to this agent */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Provenance\ProvenanceAgent',
        )]
        public array $agent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
