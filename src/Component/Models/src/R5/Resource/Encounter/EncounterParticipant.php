<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @description The list of people responsible for providing the service.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.participant', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'enc-1',
    severity: 'error',
    expression: 'actor.exists() or type.exists()',
    human: 'A type must be provided when no explicit actor is specified',
)]
#[FHIRPathInvariant(
    key: 'enc-2',
    severity: 'error',
    expression: 'actor.exists(resolve() is Patient or resolve() is Group) implies type.exists().not()',
    human: 'A type cannot be provided for a patient or group participant',
)]
class EncounterParticipant extends BackboneElement
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
        /** @var array<CodeableConcept> type Role of participant in encounter */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/encounter-participant-type', strength: 'extensible')]
        public array $type = [],
        /** @var Period|null period Period of time during the encounter that the participant participated */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var Reference|null actor The individual, device, or service participating in the encounter */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Group',
            'http://hl7.org/fhir/StructureDefinition/RelatedPerson',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
            'http://hl7.org/fhir/StructureDefinition/Device',
            'http://hl7.org/fhir/StructureDefinition/HealthcareService',
        ])]
        public ?Reference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
