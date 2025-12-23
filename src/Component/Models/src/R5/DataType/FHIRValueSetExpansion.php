<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ValueSet.expansion
 * @description A value set can also be "expanded", where the value set is turned into a simple collection of enumerated codes. This element holds the expansion, if it has been performed.
 */
class FHIRValueSetExpansion extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri identifier Identifies the value set expansion (business identifier) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri next Opaque urls for paging through expansion results */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $next = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime timestamp Time ValueSet expansion happened */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime $timestamp = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger total Total number of codes in the expansion */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $total = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger offset Offset at which this resource starts */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInteger $offset = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetExpansionParameter> parameter Parameter that controlled the expansion process */
		public array $parameter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetExpansionProperty> property Additional information supplied about each concept */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRValueSetExpansionContains> contains Codes in the value set */
		public array $contains = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
