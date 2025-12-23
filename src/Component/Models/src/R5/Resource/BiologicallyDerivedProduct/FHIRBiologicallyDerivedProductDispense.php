<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProductDispense
 * @description This resource reflects an instance of a biologically derived product dispense. The supply or dispense of a biologically derived product from the supply organization or department (e.g. hospital transfusion laboratory) to the clinical team responsible for clinical application.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'BiologicallyDerivedProductDispense',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProductDispense',
	fhirVersion: 'R5',
)]
class FHIRBiologicallyDerivedProductDispense extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business identifier for this dispense */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> basedOn The order or request that this dispense is fulfilling */
		public array $basedOn = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> partOf Short description */
		public array $partOf = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBiologicallyDerivedProductDispenseCodesType status preparation | in-progress | allocated | issued | unfulfilled | returned | entered-in-error | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRBiologicallyDerivedProductDispenseCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept originRelationshipType Relationship between the donor and intended recipient */
		public ?FHIRCodeableConcept $originRelationshipType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference product The BiologicallyDerivedProduct that is dispensed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $product = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference patient The intended recipient of the dispensed product */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept matchStatus Indicates the type of matching associated with the dispense */
		public ?FHIRCodeableConcept $matchStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBiologicallyDerivedProductDispensePerformer> performer Indicates who or what performed an action */
		public array $performer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Where the dispense occurred */
		public ?FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity quantity Amount dispensed */
		public ?FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime preparedDate When product was selected/matched */
		public ?FHIRDateTime $preparedDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime whenHandedOver When the product was dispatched */
		public ?FHIRDateTime $whenHandedOver = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference destination Where the product was dispatched to */
		public ?FHIRReference $destination = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Additional notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string usageInstruction Specific instructions for use */
		public FHIRString|string|null $usageInstruction = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
