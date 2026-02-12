<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

/**
 * @description Filter Properties supported.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'TerminologyCapabilities',
	elementPath: 'TerminologyCapabilities.codeSystem.version.filter',
	fhirVersion: 'R4',
)]
class TerminologyCapabilitiesCodeSystemVersionFilter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive code Code of the property supported */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive> op Operations supported for the property */
		public array $op = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
