<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Whether a treatment instruction (e.g. artificial respiration: yes or no) was verified with the patient, his/her family or another authorized person.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.verification', fhirVersion: 'R5')]
class ConsentVerification extends BackboneElement
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
        /** @var bool|null verified Has been verified */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $verified = null,
        /** @var CodeableConcept|null verificationType Business case of verification */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $verificationType = null,
        /** @var Reference|null verifiedBy Person conducting verification */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Organization',
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
        ])]
        public ?Reference $verifiedBy = null,
        /** @var Reference|null verifiedWith Person who verified */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/RelatedPerson',
        ])]
        public ?Reference $verifiedWith = null,
        /** @var array<DateTimePrimitive> verificationDate When consent verified */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isArray: true)]
        public array $verificationDate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
