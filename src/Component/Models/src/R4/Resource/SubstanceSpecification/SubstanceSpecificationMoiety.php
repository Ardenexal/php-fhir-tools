<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

/**
 * @description Moiety, for structural modifications.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.moiety', fhirVersion: 'R4')]
class SubstanceSpecificationMoiety extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept role Role that the moiety is playing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Identifier by which this moiety substance is known */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Textual name for this moiety substance */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept stereochemistry Stereochemistry type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $stereochemistry = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept opticalActivity Optical activity type */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $opticalActivity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string molecularFormula Molecular formula */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $molecularFormula = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string amountX Quantitative value for this moiety */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $amountX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
