<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\EpisodeOfCareStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare\EpisodeOfCareDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare\EpisodeOfCareReason;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare\EpisodeOfCareStatusHistory;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EpisodeOfCare
 *
 * @description An association between a patient and an organization / healthcare provider(s) during which time encounters may occur. The managing organization assumes a level of responsibility for the patient during this time.
 */
#[FhirResource(type: 'EpisodeOfCare', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/EpisodeOfCare', fhirVersion: 'R5')]
class EpisodeOfCareResource extends DomainResourceResource
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
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
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
        /** @var array<Identifier> identifier Business Identifier(s) relevant for this EpisodeOfCare */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var EpisodeOfCareStatusType|null status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?EpisodeOfCareStatusType $status = null,
        /** @var array<EpisodeOfCareStatusHistory> statusHistory Past list of status codes (the current status may be included to cover the start date of the status) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare\EpisodeOfCareStatusHistory',
        )]
        public array $statusHistory = [],
        /** @var array<CodeableConcept> type Type/class  - e.g. specialist referral, disease management */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $type = [],
        /** @var array<EpisodeOfCareReason> reason The list of medical reasons that are expected to be addressed during the episode of care */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare\EpisodeOfCareReason',
        )]
        public array $reason = [],
        /** @var array<EpisodeOfCareDiagnosis> diagnosis The list of medical conditions that were addressed during the episode of care */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare\EpisodeOfCareDiagnosis',
        )]
        public array $diagnosis = [],
        /** @var Reference|null patient The patient who is the focus of this episode of care */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null managingOrganization Organization that assumes responsibility for care coordination */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $managingOrganization = null,
        /** @var Period|null period Interval during responsibility is assumed */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var array<Reference> referralRequest Originating Referral Request(s) */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $referralRequest = [],
        /** @var Reference|null careManager Care manager/care coordinator for the patient */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $careManager = null,
        /** @var array<Reference> careTeam Other practitioners facilitating this episode of care */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $careTeam = [],
        /** @var array<Reference> account The set of accounts that may be used for billing for this EpisodeOfCare */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $account = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
