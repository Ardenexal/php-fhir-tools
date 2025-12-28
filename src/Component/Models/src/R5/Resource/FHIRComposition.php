<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Structured Documents)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Composition
 *
 * @description A set of healthcare-related information that is assembled together into a single logical package that provides a single coherent statement of meaning, establishes its own context and that has clinical attestation with regard to who is making the statement. A Composition defines the structure and narrative content necessary for a document. However, a Composition alone does not constitute a document. Rather, the Composition must be the first entry in a Bundle where Bundle.type=document, and any other resources referenced from Composition must be included as subsequent entries in the Bundle (for example Patient, Practitioner, Encounter, etc.).
 */
#[FhirResource(type: 'Composition', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Composition', fhirVersion: 'R5')]
class FHIRComposition extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this Composition, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Version-independent identifier for the Composition */
        public array $identifier = [],
        /** @var FHIRString|string|null version An explicitly assigned identifer of a variation of the content in the Composition */
        public FHIRString|string|null $version = null,
        /** @var FHIRCompositionStatusType|null status registered | partial | preliminary | final | amended | corrected | appended | cancelled | entered-in-error | deprecated | unknown */
        #[NotBlank]
        public ?FHIRCompositionStatusType $status = null,
        /** @var FHIRCodeableConcept|null type Kind of composition (LOINC if possible) */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> category Categorization of Composition */
        public array $category = [],
        /** @var array<FHIRReference> subject Who and/or what the composition is about */
        public array $subject = [],
        /** @var FHIRReference|null encounter Context of the Composition */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null date Composition editing time */
        #[NotBlank]
        public ?FHIRDateTime $date = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRReference> author Who and/or what authored the composition */
        public array $author = [],
        /** @var FHIRString|string|null name Name for this Composition (computer friendly) */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Human Readable name/title */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var array<FHIRAnnotation> note For any additional notes */
        public array $note = [],
        /** @var array<FHIRCompositionAttester> attester Attests to accuracy of composition */
        public array $attester = [],
        /** @var FHIRReference|null custodian Organization which maintains the composition */
        public ?FHIRReference $custodian = null,
        /** @var array<FHIRRelatedArtifact> relatesTo Relationships to other compositions/documents */
        public array $relatesTo = [],
        /** @var array<FHIRCompositionEvent> event The clinical service(s) being documented */
        public array $event = [],
        /** @var array<FHIRCompositionSection> section Composition is broken into sections */
        public array $section = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
