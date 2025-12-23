<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SubstanceSourceMaterial.organism
 * @description This subclause describes the organism which the substance is derived from. For vaccines, the parent organism shall be specified based on these subclause elements. As an example, full taxonomy will be described for the Substance Name: ., Leaf.
 */
class FHIRSubstanceSourceMaterialOrganism extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept family The family of an organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $family = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept genus The genus of an organism shall be specified; refers to the Latin epithet of the genus element of the plant/animal scientific name; it is present in names for genera, species and infraspecies */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $genus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept species The species of an organism shall be specified; refers to the Latin epithet of the species of the plant/animal; it is present in names for species and infraspecies */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $species = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept intraspecificType The Intraspecific type of an organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $intraspecificType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string intraspecificDescription The intraspecific description of an organism shall be specified based on a controlled vocabulary. For Influenza Vaccine, the intraspecific description shall contain the syntax of the antigen in line with the WHO convention */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $intraspecificDescription = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSourceMaterialOrganismAuthor> author 4.9.13.6.1 Author type (Conditional) */
		public array $author = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSourceMaterialOrganismHybrid hybrid 4.9.13.8.1 Hybrid species maternal organism ID (Optional) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSourceMaterialOrganismHybrid $hybrid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSourceMaterialOrganismOrganismGeneral organismGeneral 4.9.13.7.1 Kingdom (Conditional) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSourceMaterialOrganismOrganismGeneral $organismGeneral = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
