<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Dosage
 * @description Indicates how the medication is/was taken or should be taken by the patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Dosage', fhirVersion: 'R5')]
class FHIRDosage extends FHIRBackboneType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger sequence The order of the dosage instructions */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string text Free text dosage instructions e.g. SIG */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> additionalInstruction Supplemental instruction or warnings to the patient - e.g. "with meals", "may cause drowsiness" */
		public array $additionalInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string patientInstruction Patient or consumer oriented instructions */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $patientInstruction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTiming timing When medication should be administered */
		public ?FHIRTiming $timing = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean asNeeded Take "as needed" */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $asNeeded = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> asNeededFor Take "as needed" (for x) */
		public array $asNeededFor = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept site Body site to administer to */
		public ?FHIRCodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept route How drug should enter body */
		public ?FHIRCodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept method Technique for administering medication */
		public ?FHIRCodeableConcept $method = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDosageDoseAndRate> doseAndRate Amount of medication administered, to be administered or typical amount to be administered */
		public array $doseAndRate = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio> maxDosePerPeriod Upper limit on medication per unit of time */
		public array $maxDosePerPeriod = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity maxDosePerAdministration Upper limit on medication per administration */
		public ?FHIRQuantity $maxDosePerAdministration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity maxDosePerLifetime Upper limit on medication per lifetime of the patient */
		public ?FHIRQuantity $maxDosePerLifetime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
