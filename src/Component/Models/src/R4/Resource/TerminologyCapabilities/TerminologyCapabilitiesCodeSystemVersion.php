<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

/**
 * @description For the code system, a list of versions that are supported by the server.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem.version', fhirVersion: 'R4')]
class TerminologyCapabilitiesCodeSystemVersion extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string code Version identifier for this version */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $code = null,
		/** @var null|bool isDefault If this is the default version for this code system */
		public ?bool $isDefault = null,
		/** @var null|bool compositional If compositional grammar is supported */
		public ?bool $compositional = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive> language Language Displays supported */
		public array $language = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities\TerminologyCapabilitiesCodeSystemVersionFilter> filter Filter Properties supported */
		public array $filter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive> property Properties supported for $lookup */
		public array $property = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
