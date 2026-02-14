<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Provenance;

/**
 * @description An actor taking a role in an activity  for which it can be assigned some degree of responsibility for the activity taking place.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Provenance', elementPath: 'Provenance.agent', fhirVersion: 'R4')]
class ProvenanceAgent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type How the agent participated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> role What the agents role was */
		public array $role = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference who Who participated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference onBehalfOf Who the agent is representing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $onBehalfOf = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
