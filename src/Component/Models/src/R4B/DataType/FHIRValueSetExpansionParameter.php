<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ValueSet.expansion.parameter
 * @description A parameter that controlled the expansion process. These parameters may be used by users of expanded value sets to check whether the expansion is suitable for a particular purpose, or to pick the correct expansion.
 */
class FHIRValueSetExpansionParameter extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Name as assigned by the client or server */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime valueX Value of the named parameter */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
