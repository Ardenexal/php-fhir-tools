<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Period
 * @description A time period defined by a start and end date and optionally time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Period', fhirVersion: 'R4')]
class FHIRPeriod extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime start Starting time with inclusive boundary */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $start = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime end End time with inclusive boundary, if not ongoing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $end = null,
	) {
		parent::__construct($id, $extension);
	}
}
