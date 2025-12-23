<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstanceSourceMaterial.organism.hybrid
 * @description 4.9.13.8.1 Hybrid species maternal organism ID (Optional).
 */
class FHIRSubstanceSourceMaterialOrganismHybrid extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string maternalOrganismId The identifier of the maternal species constituting the hybrid organism shall be specified based on a controlled vocabulary. For plants, the parents aren’t always known, and it is unlikely that it will be known which is maternal and which is paternal */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $maternalOrganismId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string maternalOrganismName The name of the maternal species constituting the hybrid organism shall be specified. For plants, the parents aren’t always known, and it is unlikely that it will be known which is maternal and which is paternal */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $maternalOrganismName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string paternalOrganismId The identifier of the paternal species constituting the hybrid organism shall be specified based on a controlled vocabulary */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $paternalOrganismId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string paternalOrganismName The name of the paternal species constituting the hybrid organism shall be specified */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $paternalOrganismName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept hybridType The hybrid type of an organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $hybridType = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
