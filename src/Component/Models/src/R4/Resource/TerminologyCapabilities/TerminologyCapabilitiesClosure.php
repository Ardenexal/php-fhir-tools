<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

/**
 * @description Whether the $closure operation is supported.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.closure', fhirVersion: 'R4')]
class TerminologyCapabilitiesClosure extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool translation If cross-system closure is supported */
		public ?bool $translation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
