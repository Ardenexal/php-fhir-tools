<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Dosage
 * @description Indicates how the medication is/was taken or should be taken by the patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Dosage', elementPath: 'Dosage', fhirVersion: 'R4B')]
class FHIRDosage extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger sequence The order of the dosage instructions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string text Free text dosage instructions e.g. SIG */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> additionalInstruction Supplemental instruction or warnings to the patient - e.g. "with meals", "may cause drowsiness" */
		public array $additionalInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string patientInstruction Patient or consumer oriented instructions */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $patientInstruction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming timing When medication should be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming $timing = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept asNeededX Take "as needed" (for x) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept|null $asNeededX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept site Body site to administer to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept route How drug should enter body */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept method Technique for administering medication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $method = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDosageDoseAndRate> doseAndRate Amount of medication administered */
		public array $doseAndRate = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio maxDosePerPeriod Upper limit on medication per unit of time */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio $maxDosePerPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity maxDosePerAdministration Upper limit on medication per administration */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $maxDosePerAdministration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity maxDosePerLifetime Upper limit on medication per lifetime of the patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $maxDosePerLifetime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
