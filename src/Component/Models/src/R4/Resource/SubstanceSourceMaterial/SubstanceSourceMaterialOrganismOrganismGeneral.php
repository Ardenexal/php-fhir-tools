<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial;

/**
 * @description 4.9.13.7.1 Kingdom (Conditional).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SubstanceSourceMaterial',
	elementPath: 'SubstanceSourceMaterial.organism.organismGeneral',
	fhirVersion: 'R4',
)]
class SubstanceSourceMaterialOrganismOrganismGeneral extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept kingdom The kingdom of an organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $kingdom = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept phylum The phylum of an organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $phylum = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept class The class of an organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $class = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept order The order of an organism shall be specified, */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $order = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
