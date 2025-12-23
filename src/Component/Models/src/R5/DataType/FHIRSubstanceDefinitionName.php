<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element SubstanceDefinition.name
 * @description Names applicable to this substance.
 */
class FHIRSubstanceDefinitionName extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name The actual name */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept type Name type e.g. 'systematic',  'scientific, 'brand' */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept status The status of the name e.g. 'current', 'proposed' */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean preferred If this is the preferred name for this substance */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $preferred = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> language Human language that the name is written in */
		public array $language = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> domain The use context of this name e.g. as an active ingredient or as a food colour additive */
		public array $domain = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> jurisdiction The jurisdiction where this name applies */
		public array $jurisdiction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceDefinitionName> synonym A synonym of this particular name, by which the substance is also known */
		public array $synonym = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceDefinitionName> translation A translation for this name into another human language */
		public array $translation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceDefinitionNameOfficial> official Details of the official nature of this name */
		public array $official = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> source Supporting literature */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
