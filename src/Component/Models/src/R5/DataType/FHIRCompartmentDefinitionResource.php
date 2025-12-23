<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element CompartmentDefinition.resource
 * @description Information about how a resource is related to the compartment.
 */
class FHIRCompartmentDefinitionResource extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResourceTypeType code Name of resource type */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResourceTypeType $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> param Search Parameter Name, or chained parameters */
		public array $param = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string documentation Additional documentation about the resource and compartment */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri startParam Search Param for interpreting $everything.start */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $startParam = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri endParam Search Param for interpreting $everything.end */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $endParam = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
