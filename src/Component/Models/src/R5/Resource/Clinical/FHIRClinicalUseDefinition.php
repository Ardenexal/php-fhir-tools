<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition
 *
 * @description A single issue - either an indication, contraindication, interaction or an undesirable effect for a medicinal product, medication, device or procedure.
 */
#[FhirResource(
    type: 'ClinicalUseDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ClinicalUseDefinition',
    fhirVersion: 'R5',
)]
class FHIRClinicalUseDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this issue */
        public array $identifier = [],
        /** @var FHIRClinicalUseDefinitionTypeType|null type indication | contraindication | interaction | undesirable-effect | warning */
        #[NotBlank]
        public ?FHIRClinicalUseDefinitionTypeType $type = null,
        /** @var array<FHIRCodeableConcept> category A categorisation of the issue, primarily for dividing warnings into subject heading areas such as "Pregnancy", "Overdose" */
        public array $category = [],
        /** @var array<FHIRReference> subject The medication, product, substance, device, procedure etc. for which this is an indication */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null status Whether this is a current issue or one that has been retired etc */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRClinicalUseDefinitionContraindication|null contraindication Specifics for when this is a contraindication */
        public ?FHIRClinicalUseDefinitionContraindication $contraindication = null,
        /** @var FHIRClinicalUseDefinitionIndication|null indication Specifics for when this is an indication */
        public ?FHIRClinicalUseDefinitionIndication $indication = null,
        /** @var FHIRClinicalUseDefinitionInteraction|null interaction Specifics for when this is an interaction */
        public ?FHIRClinicalUseDefinitionInteraction $interaction = null,
        /** @var array<FHIRReference> population The population group to which this applies */
        public array $population = [],
        /** @var array<FHIRCanonical> library Logic used by the clinical use definition */
        public array $library = [],
        /** @var FHIRClinicalUseDefinitionUndesirableEffect|null undesirableEffect A possible negative outcome from the use of this treatment */
        public ?FHIRClinicalUseDefinitionUndesirableEffect $undesirableEffect = null,
        /** @var FHIRClinicalUseDefinitionWarning|null warning Critical environmental, health or physical risks or hazards. For example 'Do not operate heavy machinery', 'May cause drowsiness' */
        public ?FHIRClinicalUseDefinitionWarning $warning = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
