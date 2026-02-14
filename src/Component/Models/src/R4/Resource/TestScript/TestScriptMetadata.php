<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

/**
 * @description The required capability must exist and are assumed to function correctly on the FHIR server being tested.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata', fhirVersion: 'R4')]
class TestScriptMetadata extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptMetadataLink> link Links to the FHIR specification */
		public array $link = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptMetadataCapability> capability Capabilities  that are assumed to function correctly on the FHIR server being tested */
		public array $capability = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
