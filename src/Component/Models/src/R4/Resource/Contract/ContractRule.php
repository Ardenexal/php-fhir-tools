<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

/**
 * @description List of Computable Policy Rule Language Representations of this Contract.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.rule', fhirVersion: 'R4')]
class ContractRule extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference contentX Computable Contract Rules */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Attachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $contentX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
