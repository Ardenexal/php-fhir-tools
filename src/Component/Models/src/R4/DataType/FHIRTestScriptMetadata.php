<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element TestScript.metadata
 * @description The required capability must exist and are assumed to function correctly on the FHIR server being tested.
 */
class FHIRTestScriptMetadata extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestScriptMetadataLink> link Links to the FHIR specification */
		public array $link = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTestScriptMetadataCapability> capability Capabilities  that are assumed to function correctly on the FHIR server being tested */
		public array $capability = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
