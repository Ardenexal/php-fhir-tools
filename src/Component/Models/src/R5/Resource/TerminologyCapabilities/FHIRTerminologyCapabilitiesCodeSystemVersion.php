<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description For the code system, a list of versions that are supported by the server.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem.version', fhirVersion: 'R5')]
class FHIRTerminologyCapabilitiesCodeSystemVersion extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string code Version identifier for this version */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean isDefault If this is the default version for this code system */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $isDefault = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean compositional If compositional grammar is supported */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $compositional = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCommonLanguagesType> language Language Displays supported */
		public array $language = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRTerminologyCapabilitiesCodeSystemVersionFilter> filter Filter Properties supported */
		public array $filter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode> property Properties supported for $lookup */
		public array $property = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
