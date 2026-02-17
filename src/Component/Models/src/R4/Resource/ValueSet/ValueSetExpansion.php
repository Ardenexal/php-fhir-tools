<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

/**
 * @description A value set can also be "expanded", where the value set is turned into a simple collection of enumerated codes. This element holds the expansion, if it has been performed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion', fhirVersion: 'R4')]
class ValueSetExpansion extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive identifier Identifies the value set expansion (business identifier) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive timestamp Time ValueSet expansion happened */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $timestamp = null,
		/** @var null|int total Total number of codes in the expansion */
		public ?int $total = null,
		/** @var null|int offset Offset at which this resource starts */
		public ?int $offset = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetExpansionParameter> parameter Parameter that controlled the expansion process */
		public array $parameter = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetExpansionContains> contains Codes in the value set */
		public array $contains = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
