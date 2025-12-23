<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element TestScript.metadata.link
 * @description A link to the FHIR specification that this test is covering.
 */
class FHIRTestScriptMetadataLink extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url URL to the specification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Short description */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
