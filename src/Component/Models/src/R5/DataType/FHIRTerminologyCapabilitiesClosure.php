<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element TerminologyCapabilities.closure
 * @description Whether the $closure operation is supported.
 */
class FHIRTerminologyCapabilitiesClosure extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean translation If cross-system closure is supported */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $translation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
