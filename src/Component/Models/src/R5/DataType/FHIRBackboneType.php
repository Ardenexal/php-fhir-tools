<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/BackboneType
 * @description Base definition for the few data types that are allowed to carry modifier extensions.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'BackboneType', fhirVersion: 'R5')]
abstract class FHIRBackboneType extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
	) {
		parent::__construct($id, $extension);
	}
}
