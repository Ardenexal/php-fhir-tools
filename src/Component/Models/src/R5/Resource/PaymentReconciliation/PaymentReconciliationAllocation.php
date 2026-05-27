<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\PaymentReconciliation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Money;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description Distribution of the payment amount for a previously acknowledged payable.
 */
#[FHIRBackboneElement(parentResource: 'PaymentReconciliation', elementPath: 'PaymentReconciliation.allocation', fhirVersion: 'R5')]
class PaymentReconciliationAllocation extends BackboneElement
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
        /** @var Identifier|null identifier Business identifier of the payment detail */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var Identifier|null predecessor Business identifier of the prior payment detail */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $predecessor = null,
        /** @var Reference|null target Subject of the payment */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Claim',
            'http://hl7.org/fhir/StructureDefinition/Account',
            'http://hl7.org/fhir/StructureDefinition/Invoice',
            'http://hl7.org/fhir/StructureDefinition/ChargeItem',
            'http://hl7.org/fhir/StructureDefinition/Encounter',
            'http://hl7.org/fhir/StructureDefinition/Contract',
        ])]
        public ?Reference $target = null,
        /** @var StringPrimitive|string|Identifier|PositiveIntPrimitive|null targetItem Sub-element of the subject */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'targetItemString',
                ],
                [
                    'fhirType'     => 'Identifier',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
                    'jsonKey'      => 'targetItemIdentifier',
                ],
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'targetItemPositiveInt',
                ],
            ],
        )]
        public StringPrimitive|string|Identifier|PositiveIntPrimitive|null $targetItem = null,
        /** @var Reference|null encounter Applied-to encounter */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Encounter'])]
        public ?Reference $encounter = null,
        /** @var Reference|null account Applied-to account */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Account'])]
        public ?Reference $account = null,
        /** @var CodeableConcept|null type Category of payment */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/payment-type', strength: 'extensible')]
        public ?CodeableConcept $type = null,
        /** @var Reference|null submitter Submitter of the request */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
            'http://hl7.org/fhir/StructureDefinition/Organization',
        ])]
        public ?Reference $submitter = null,
        /** @var Reference|null response Response committing to a payment */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ClaimResponse'])]
        public ?Reference $response = null,
        /** @var DatePrimitive|null date Date of commitment to pay */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $date = null,
        /** @var Reference|null responsible Contact for the response */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/PractitionerRole'])]
        public ?Reference $responsible = null,
        /** @var Reference|null payee Recipient of the payment */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
            'http://hl7.org/fhir/StructureDefinition/Organization',
        ])]
        public ?Reference $payee = null,
        /** @var Money|null amount Amount allocated to this payable */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
