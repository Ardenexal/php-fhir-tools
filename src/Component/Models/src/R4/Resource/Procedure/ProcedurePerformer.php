<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Procedure;

/**
 * @description Limited to "real" people rather than equipment.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Procedure', elementPath: 'Procedure.performer', fhirVersion: 'R4')]
class ProcedurePerformer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept function Type of performance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $function = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference actor The reference to the practitioner */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $actor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference onBehalfOf Organization the device or practitioner was acting for */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $onBehalfOf = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
