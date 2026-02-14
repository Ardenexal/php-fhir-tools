<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization;

/**
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.protocolApplied', fhirVersion: 'R4')]
class ImmunizationProtocolApplied extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string series Name of vaccine series */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference authority Who is responsible for publishing the recommendations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $authority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> targetDisease Vaccine preventatable disease being targetted */
		public array $targetDisease = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string doseNumberX Dose number within series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $doseNumberX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string seriesDosesX Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $seriesDosesX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
