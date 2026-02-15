<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CompositionStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\V3ConfidentialityClassificationType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionAttester;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionEvent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionRelatesTo;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Composition\CompositionSection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Structured Documents)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Composition
 *
 * @description A set of healthcare-related information that is assembled together into a single logical package that provides a single coherent statement of meaning, establishes its own context and that has clinical attestation with regard to who is making the statement. A Composition defines the structure and narrative content necessary for a document. However, a Composition alone does not constitute a document. Rather, the Composition must be the first entry in a Bundle where Bundle.type=document, and any other resources referenced from Composition must be included as subsequent entries in the Bundle (for example Patient, Practitioner, Encounter, etc.).
 */
#[FhirResource(type: 'Composition', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Composition', fhirVersion: 'R4')]
class CompositionResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Version-independent identifier for the Composition */
        public ?Identifier $identifier = null,
        /** @var CompositionStatusType|null status preliminary | final | amended | entered-in-error */
        #[NotBlank]
        public ?CompositionStatusType $status = null,
        /** @var CodeableConcept|null type Kind of composition (LOINC if possible) */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> category Categorization of Composition */
        public array $category = [],
        /** @var Reference|null subject Who and/or what the composition is about */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Context of the Composition */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null date Composition editing time */
        #[NotBlank]
        public ?DateTimePrimitive $date = null,
        /** @var array<Reference> author Who and/or what authored the composition */
        public array $author = [],
        /** @var StringPrimitive|string|null title Human Readable name/title */
        #[NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var V3ConfidentialityClassificationType|null confidentiality As defined by affinity domain */
        public ?V3ConfidentialityClassificationType $confidentiality = null,
        /** @var array<CompositionAttester> attester Attests to accuracy of composition */
        public array $attester = [],
        /** @var Reference|null custodian Organization which maintains the composition */
        public ?Reference $custodian = null,
        /** @var array<CompositionRelatesTo> relatesTo Relationships to other compositions/documents */
        public array $relatesTo = [],
        /** @var array<CompositionEvent> event The clinical service(s) being documented */
        public array $event = [],
        /** @var array<CompositionSection> section Composition is broken into sections */
        public array $section = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
