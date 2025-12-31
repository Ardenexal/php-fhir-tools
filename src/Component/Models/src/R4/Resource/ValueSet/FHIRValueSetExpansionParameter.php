<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description A parameter that controlled the expansion process. These parameters may be used by users of expanded value sets to check whether the expansion is suitable for a particular purpose, or to pick the correct expansion.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion.parameter', fhirVersion: 'R4')]
class FHIRValueSetExpansionParameter extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string name Name as assigned by the client or server */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime valueX Value of the named parameter */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
