<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CapabilityStatement.rest
 * @description A definition of the restful capabilities of the solution, if any.
 */
class FHIRCapabilityStatementRest extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRestfulCapabilityModeType mode client | server */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRestfulCapabilityModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown documentation General description of implementation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestSecurity security Information about security of implementation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestSecurity $security = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestResource> resource Resource served on the REST interface */
		public array $resource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestInteraction> interaction What operations are supported? */
		public array $interaction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestResourceSearchParam> searchParam Search parameters for searching all resources */
		public array $searchParam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCapabilityStatementRestResourceOperation> operation Definition of a system level operation */
		public array $operation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical> compartment Compartments served/used by system */
		public array $compartment = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
