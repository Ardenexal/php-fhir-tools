<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

/**
 * @description An abstract server used in operations within this test script in the destination element.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.destination', fhirVersion: 'R4')]
class TestScriptDestination extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|int index The index of the abstract destination server starting at 1 */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?int $index = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding profile FHIR-Server | FHIR-SDC-FormManager | FHIR-SDC-FormReceiver | FHIR-SDC-FormProcessor */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $profile = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
