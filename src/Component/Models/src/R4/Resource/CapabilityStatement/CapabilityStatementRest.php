<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

/**
 * @description A definition of the restful capabilities of the solution, if any.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest', fhirVersion: 'R4')]
class CapabilityStatementRest extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\RestfulCapabilityModeType mode client | server */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RestfulCapabilityModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive documentation General description of implementation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestSecurity security Information about security of implementation */
		public ?CapabilityStatementRestSecurity $security = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestResource> resource Resource served on the REST interface */
		public array $resource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestInteraction> interaction What operations are supported? */
		public array $interaction = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestResourceSearchParam> searchParam Search parameters for searching all resources */
		public array $searchParam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementRestResourceOperation> operation Definition of a system level operation */
		public array $operation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive> compartment Compartments served/used by system */
		public array $compartment = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
