<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Dosage
 * @description Indicates how the medication is/was taken or should be taken by the patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Dosage', elementPath: 'Dosage', fhirVersion: 'R4')]
class Dosage extends BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|int sequence The order of the dosage instructions */
		public ?int $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Free text dosage instructions e.g. SIG */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> additionalInstruction Supplemental instruction or warnings to the patient - e.g. "with meals", "may cause drowsiness" */
		public array $additionalInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string patientInstruction Patient or consumer oriented instructions */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $patientInstruction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing timing When medication should be administered */
		public ?Timing $timing = null,
		/** @var null|bool|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept asNeededX Take "as needed" (for x) */
		public bool|CodeableConcept|null $asNeededX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept site Body site to administer to */
		public ?CodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept route How drug should enter body */
		public ?CodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method Technique for administering medication */
		public ?CodeableConcept $method = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\DosageDoseAndRate> doseAndRate Amount of medication administered */
		public array $doseAndRate = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio maxDosePerPeriod Upper limit on medication per unit of time */
		public ?Ratio $maxDosePerPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity maxDosePerAdministration Upper limit on medication per administration */
		public ?Quantity $maxDosePerAdministration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity maxDosePerLifetime Upper limit on medication per lifetime of the patient */
		public ?Quantity $maxDosePerLifetime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
