<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFlagStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Care)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Flag
 *
 * @description Prospective warnings of potential issues when providing care to the patient.
 */
#[FhirResource(type: 'Flag', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Flag', fhirVersion: 'R5')]
class FHIRFlag extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRFlagStatusType|null status active | inactive | entered-in-error */
        #[NotBlank]
        public ?FHIRFlagStatusType $status = null,
        /** @var array<FHIRCodeableConcept> category Clinical, administrative, etc */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Coded or textual message to display to user */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRReference|null subject Who/What is flag about? */
        #[NotBlank]
        public ?FHIRReference $subject = null,
        /** @var FHIRPeriod|null period Time period when flag is active */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null encounter Alert relevant during encounter */
        public ?FHIRReference $encounter = null,
        /** @var FHIRReference|null author Flag creator */
        public ?FHIRReference $author = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
