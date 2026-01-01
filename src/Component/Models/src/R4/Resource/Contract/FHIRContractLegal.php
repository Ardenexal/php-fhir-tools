<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description List of Legal expressions or representations of this Contract.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.legal', fhirVersion: 'R4')]
class FHIRContractLegal extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference contentX Contract Legal Text */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|null $contentX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
