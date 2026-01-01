<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Element
 * @description Base definition for all elements in a resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Element', fhirVersion: 'R4')]
abstract class FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
	) {
	}
}
