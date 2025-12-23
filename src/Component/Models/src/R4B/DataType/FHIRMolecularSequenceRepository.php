<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MolecularSequence.repository
 * @description Configurations of the external repository. The repository shall store target's observedSeq or records related with target's observedSeq.
 */
class FHIRMolecularSequenceRepository extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRepositoryTypeType type directlink | openapi | login | oauth | other */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRepositoryTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri url URI of the repository */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Repository's name */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string datasetId Id of the dataset that used to call for dataset in repository */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $datasetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string variantsetId Id of the variantset that used to call for variantset in repository */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $variantsetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string readsetId Id of the read */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $readsetId = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
