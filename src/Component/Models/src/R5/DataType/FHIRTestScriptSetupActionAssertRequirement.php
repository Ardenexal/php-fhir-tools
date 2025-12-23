<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element TestScript.setup.action.assert.requirement
 * @description Links or references providing traceability to the testing requirements for this assert.
 */
class FHIRTestScriptSetupActionAssertRequirement extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical linkX Link or reference to the testing requirement */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical|null $linkX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
