<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceCriticalityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AllergyIntoleranceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AllergyIntolerance\AllergyIntoleranceReaction;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AllergyIntolerance
 *
 * @description Risk of harmful or undesirable, physiological response which is unique to an individual and associated with exposure to a substance.
 */
#[FhirResource(
    type: 'AllergyIntolerance',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/AllergyIntolerance',
    fhirVersion: 'R4',
)]
class AllergyIntoleranceResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier External ids for this item */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var CodeableConcept|null clinicalStatus active | inactive | resolved */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $clinicalStatus = null,
        /** @var CodeableConcept|null verificationStatus unconfirmed | confirmed | refuted | entered-in-error */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $verificationStatus = null,
        /** @var AllergyIntoleranceTypeType|null type allergy | intolerance - Underlying mechanism (if known) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllergyIntoleranceTypeType $type = null,
        /** @var array<AllergyIntoleranceCategoryType> category food | medication | environment | biologic */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $category = [],
        /** @var AllergyIntoleranceCriticalityType|null criticality low | high | unable-to-assess */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllergyIntoleranceCriticalityType $criticality = null,
        /** @var CodeableConcept|null code Code that identifies the allergy or intolerance */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var Reference|null patient Who the sensitivity is for */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null encounter Encounter when the allergy or intolerance was asserted */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null onset When allergy or intolerance was identified */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'onsetDateTime',
                ],
                [
                    'fhirType'     => 'Age',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Age',
                    'jsonKey'      => 'onsetAge',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Period',
                    'jsonKey'      => 'onsetPeriod',
                ],
                [
                    'fhirType'     => 'Range',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Range',
                    'jsonKey'      => 'onsetRange',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'onsetString',
                ],
            ],
        )]
        public DateTimePrimitive|Age|Period|Range|StringPrimitive|string|null $onset = null,
        /** @var DateTimePrimitive|null recordedDate Date first version of the resource instance was recorded */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $recordedDate = null,
        /** @var Reference|null recorder Who recorded the sensitivity */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $recorder = null,
        /** @var Reference|null asserter Source of the information about the allergy */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $asserter = null,
        /** @var DateTimePrimitive|null lastOccurrence Date(/time) of last known occurrence of a reaction */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $lastOccurrence = null,
        /** @var array<Annotation> note Additional text not captured in other fields */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
        )]
        public array $note = [],
        /** @var array<AllergyIntoleranceReaction> reaction Adverse Reaction Events linked to exposure to substance */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\AllergyIntolerance\AllergyIntoleranceReaction',
        )]
        public array $reaction = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
