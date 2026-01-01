<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/BodyStructure
 *
 * @description Record details about an anatomical structure.  This resource may be used when a coded concept does not provide the necessary detail needed for the use case.
 */
#[FhirResource(type: 'BodyStructure', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/BodyStructure', fhirVersion: 'R5')]
class FHIRBodyStructure extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Bodystructure identifier */
        public array $identifier = [],
        /** @var FHIRBoolean|null active Whether this record is in active use */
        public ?FHIRBoolean $active = null,
        /** @var FHIRCodeableConcept|null morphology Kind of Structure */
        public ?FHIRCodeableConcept $morphology = null,
        /** @var array<FHIRBodyStructureIncludedStructure> includedStructure Included anatomic location(s) */
        public array $includedStructure = [],
        /** @var array<FHIRBodyStructureIncludedStructure> excludedStructure Excluded anatomic locations(s) */
        public array $excludedStructure = [],
        /** @var FHIRMarkdown|null description Text description */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAttachment> image Attached images */
        public array $image = [],
        /** @var FHIRReference|null patient Who this is about */
        #[NotBlank]
        public ?FHIRReference $patient = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
