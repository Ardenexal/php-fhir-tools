<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Provenance;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking a role in an activity  for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.agent', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'prov-1',
    severity: 'error',
    expression: 'who.resolve().exists() and onBehalfOf.resolve().exists() implies who.resolve() != onBehalfOf.resolve()',
    human: 'Who and onBehalfOf cannot be the same',
)]
#[FHIRPathInvariant(
    key: 'prov-2',
    severity: 'error',
    expression: 'who.resolve().ofType(PractitionerRole).practitioner.resolve().exists() and onBehalfOf.resolve().ofType(Practitioner).exists() implies who.resolve().practitioner.resolve() != onBehalfOf.resolve()',
    human: 'If who is a PractitionerRole, onBehalfOf can\'t reference the same Practitioner',
)]
#[FHIRPathInvariant(
    key: 'prov-3',
    severity: 'error',
    expression: 'who.resolve().ofType(Organization).exists() and onBehalfOf.resolve().ofType(PractitionerRole).organization.resolve().exists() implies who.resolve() != onBehalfOf.resolve().organization.resolve()',
    human: 'If who is an organization, onBehalfOf can\'t be a PractitionerRole within that organization',
)]
class ProvenanceAgent extends BackboneElement
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
        /** @var CodeableConcept|null type How the agent participated */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> role What the agents role was */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $role = [],
        /** @var Reference|null who The agent that participated in the event */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $who = null,
        /** @var Reference|null onBehalfOf The agent that delegated */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $onBehalfOf = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
