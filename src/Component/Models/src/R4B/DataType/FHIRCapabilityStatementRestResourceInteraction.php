<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element CapabilityStatement.rest.resource.interaction
 * @description Identifies a restful operation supported by the solution.
 */
class FHIRCapabilityStatementRestResourceInteraction extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTypeRestfulInteractionType code read | vread | update | patch | delete | history-instance | history-type | create | search-type */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTypeRestfulInteractionType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown documentation Anything special about operation behavior */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $documentation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
