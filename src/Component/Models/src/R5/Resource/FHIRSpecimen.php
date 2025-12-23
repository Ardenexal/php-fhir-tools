<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Specimen
 * @description A sample to be used for analysis.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Specimen', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Specimen', fhirVersion: 'R5')]
class FHIRSpecimen extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier External Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier accessionIdentifier Identifier assigned by the lab */
		public ?FHIRIdentifier $accessionIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenStatusType status available | unavailable | unsatisfactory | entered-in-error */
		public ?FHIRSpecimenStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Kind of material that forms the specimen */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference subject Where the specimen came from. This may be from patient(s), from a location (e.g., the source of an environmental sample), or a sampling of a substance, a biologically-derived product, or a device */
		public ?FHIRReference $subject = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime receivedTime The time when specimen is received by the testing laboratory */
		public ?FHIRDateTime $receivedTime = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> parent Specimen from which this specimen originated */
		public array $parent = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> request Why the specimen was collected */
		public array $request = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenCombinedType combined grouped | pooled */
		public ?FHIRSpecimenCombinedType $combined = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> role The role the specimen serves */
		public array $role = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenFeature> feature The physical feature of a specimen */
		public array $feature = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenCollection collection Collection details */
		public ?FHIRSpecimenCollection $collection = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenProcessing> processing Processing and processing step details */
		public array $processing = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSpecimenContainer> container Direct container of specimen (tube/slide, etc.) */
		public array $container = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> condition State of the specimen */
		public array $condition = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Comments */
		public array $note = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
