<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/BodyStructure
 *
 * @description Record details about an anatomical structure.  This resource may be used when a coded concept does not provide the necessary detail needed for the use case.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'BodyStructure',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/BodyStructure',
    fhirVersion: 'R4B',
)]
class FHIRBodyStructure extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Bodystructure identifier */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this record is in active use */
        public ?\FHIRBoolean $active = null,
        /** @var FHIRCodeableConcept|null morphology Kind of Structure */
        public ?\FHIRCodeableConcept $morphology = null,
        /** @var FHIRCodeableConcept|null location Body site */
        public ?\FHIRCodeableConcept $location = null,
        /** @var array<FHIRCodeableConcept> locationQualifier Body site modifier */
        public array $locationQualifier = [],
        /** @var FHIRString|string|null description Text description */
        public \FHIRString|string|null $description = null,
        /** @var array<FHIRAttachment> image Attached images */
        public array $image = [],
        /** @var FHIRReference|null patient Who this is about */
        #[NotBlank]
        public ?\FHIRReference $patient = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
