<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductPharmaceutical
 * @description A pharmaceutical product described in terms of its composition and dose form.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProductPharmaceutical',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductPharmaceutical',
	fhirVersion: 'R4',
)]
class FHIRMedicinalProductPharmaceutical extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier An identifier for the pharmaceutical medicinal product */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept administrableDoseForm The administrable dose form, after necessary reconstitution */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $administrableDoseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept unitOfPresentation Todo */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $unitOfPresentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> ingredient Ingredient */
		public array $ingredient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> device Accompanying device */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicinalProductPharmaceuticalCharacteristics> characteristics Characteristics e.g. a products onset of action */
		public array $characteristics = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicinalProductPharmaceuticalRouteOfAdministration> routeOfAdministration The path by which the pharmaceutical product is taken into or makes contact with the body */
		public array $routeOfAdministration = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
