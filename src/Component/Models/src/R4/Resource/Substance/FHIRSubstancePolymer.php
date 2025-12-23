<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/SubstancePolymer
 * @description Todo.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SubstancePolymer',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/SubstancePolymer',
	fhirVersion: 'R4',
)]
class FHIRSubstancePolymer extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept class Todo */
		public ?FHIRCodeableConcept $class = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept geometry Todo */
		public ?FHIRCodeableConcept $geometry = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> copolymerConnectivity Todo */
		public array $copolymerConnectivity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string> modification Todo */
		public array $modification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstancePolymerMonomerSet> monomerSet Todo */
		public array $monomerSet = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstancePolymerRepeat> repeat Todo */
		public array $repeat = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
