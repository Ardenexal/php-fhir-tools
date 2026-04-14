<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @description Details about the stay during which a healthcare service is provided.
 *
 * This does not describe the event of admitting the patient, but rather any information that is relevant from the time of admittance until the time of discharge.
 */
#[FHIRBackboneElement(parentResource: 'Encounter', elementPath: 'Encounter.admission', fhirVersion: 'R5')]
class EncounterAdmission extends BackboneElement
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
        /** @var Identifier|null preAdmissionIdentifier Pre-admission identifier */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $preAdmissionIdentifier = null,
        /** @var Reference|null origin The location/organization from which the patient came before admission */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $origin = null,
        /** @var CodeableConcept|null admitSource From where patient was admitted (physician referral, transfer) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $admitSource = null,
        /** @var CodeableConcept|null reAdmission Indicates that the patient is being re-admitted */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $reAdmission = null,
        /** @var Reference|null destination Location/organization to which the patient is discharged */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $destination = null,
        /** @var CodeableConcept|null dischargeDisposition Category or kind of location after discharge */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $dischargeDisposition = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
