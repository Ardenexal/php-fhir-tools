<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

/**
 * @description Fixture in the test script - by reference (uri). All fixtures are required for the test script to execute.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.fixture', fhirVersion: 'R4')]
class TestScriptFixture extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool autocreate Whether or not to implicitly create the fixture during setup */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $autocreate = null,
		/** @var null|bool autodelete Whether or not to implicitly delete the fixture during teardown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $autodelete = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference resource Reference of the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $resource = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
