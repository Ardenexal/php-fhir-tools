<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct;

/**
 * @description Any processing of the product during collection that does not change the fundamental nature of the product. For example adding anti-coagulants during the collection of Peripheral Blood Stem Cells.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'BiologicallyDerivedProduct', elementPath: 'BiologicallyDerivedProduct.processing', fhirVersion: 'R4')]
class BiologicallyDerivedProductProcessing extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Description of of processing */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept procedure Procesing code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $procedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference additive Substance added during processing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $additive = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period timeX Time of processing */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period|null $timeX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
