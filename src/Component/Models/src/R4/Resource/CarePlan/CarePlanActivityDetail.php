<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CarePlanActivityKindType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CarePlanActivityStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Timing;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A simple summary of a planned activity suitable for a general care plan system (e.g. form driven) that doesn't know about specific resources such as procedure etc.
 */
#[FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity.detail', fhirVersion: 'R4')]
class CarePlanActivityDetail extends BackboneElement
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
        /** @var CarePlanActivityKindType|null kind Appointment | CommunicationRequest | DeviceRequest | MedicationRequest | NutritionOrder | Task | ServiceRequest | VisionPrescription */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CarePlanActivityKindType $kind = null,
        /** @var array<CanonicalPrimitive> instantiatesCanonical Instantiates FHIR protocol or definition */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $instantiatesCanonical = [],
        /** @var array<UriPrimitive> instantiatesUri Instantiates external protocol or definition */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $instantiatesUri = [],
        /** @var CodeableConcept|null code Detail type of activity */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var array<CodeableConcept> reasonCode Why activity should be done or why activity was prohibited */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why activity is needed */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $reasonReference = [],
        /** @var array<Reference> goal Goals this activity relates to */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $goal = [],
        /** @var CarePlanActivityStatusType|null status not-started | scheduled | in-progress | on-hold | completed | cancelled | stopped | unknown | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CarePlanActivityStatusType $status = null,
        /** @var CodeableConcept|null statusReason Reason for current status */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $statusReason = null,
        /** @var bool|null doNotPerform If true, activity is prohibiting action */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $doNotPerform = null,
        /** @var Timing|Period|StringPrimitive|string|null scheduled When activity is to occur */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'Timing',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Timing',
                    'jsonKey'      => 'scheduledTiming',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'scheduledPeriod',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'scheduledString',
                ],
            ],
        )]
        public Timing|Period|StringPrimitive|string|null $scheduled = null,
        /** @var Reference|null location Where it should happen */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $location = null,
        /** @var array<Reference> performer Who will be responsible? */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $performer = [],
        /** @var CodeableConcept|Reference|null product What is to be administered/supplied */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
                    'jsonKey'      => 'productCodeableConcept',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
                    'jsonKey'      => 'productReference',
                ],
            ],
        )]
        public CodeableConcept|Reference|null $product = null,
        /** @var Quantity|null dailyAmount How to consume/day? */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $dailyAmount = null,
        /** @var Quantity|null quantity How much to administer/supply/consume */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var StringPrimitive|string|null description Extra info describing activity to perform */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
