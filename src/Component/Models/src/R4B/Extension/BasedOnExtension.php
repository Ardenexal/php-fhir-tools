<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/event-basedOn
 *
 * @description A plan, proposal or order that is fulfilled in whole or in part by this event.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/event-basedOn', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'Condition')]
#[FHIRExtensionContext(type: 'element', expression: 'ChargeItem')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalImpression')]
#[FHIRExtensionContext(type: 'element', expression: 'Composition')]
#[FHIRExtensionContext(type: 'element', expression: 'Consent')]
#[FHIRExtensionContext(type: 'element', expression: 'Coverage')]
#[FHIRExtensionContext(type: 'element', expression: 'EpisodeOfCare')]
#[FHIRExtensionContext(type: 'element', expression: 'ExplanationOfBenefit')]
#[FHIRExtensionContext(type: 'element', expression: 'FamilyMemberHistory')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationEvaluation')]
#[FHIRExtensionContext(type: 'element', expression: 'InventoryReport')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'PaymentNotice')]
#[FHIRExtensionContext(type: 'element', expression: 'PaymentReconciliation')]
class BasedOnExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/event-basedOn',
            value: $this->valueReference,
        );
    }
}
