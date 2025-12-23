<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element TestScript.metadata.capability
 * @description Capabilities that must exist and are assumed to function correctly on the FHIR server being tested.
 */
class FHIRTestScriptMetadataCapability extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean required Are the capabilities required? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $required = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean validated Are the capabilities validated? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean $validated = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description The expected capabilities of the server */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger> origin Which origin server these requirements apply to */
		public array $origin = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger destination Which server these requirements apply to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger $destination = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri> link Links to the FHIR specification */
		public array $link = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical capabilities Required Capability Statement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCanonical $capabilities = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
