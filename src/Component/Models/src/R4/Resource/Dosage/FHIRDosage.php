<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Dosage
 * @description Indicates how the medication is/was taken or should be taken by the patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Dosage', elementPath: 'Dosage', fhirVersion: 'R4')]
class FHIRDosage extends FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger sequence The order of the dosage instructions */
		public ?FHIRInteger $sequence = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string text Free text dosage instructions e.g. SIG */
		public FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> additionalInstruction Supplemental instruction or warnings to the patient - e.g. "with meals", "may cause drowsiness" */
		public array $additionalInstruction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string patientInstruction Patient or consumer oriented instructions */
		public FHIRString|string|null $patientInstruction = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTiming timing When medication should be administered */
		public ?FHIRTiming $timing = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept asNeededX Take "as needed" (for x) */
		public FHIRBoolean|FHIRCodeableConcept|null $asNeededX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept site Body site to administer to */
		public ?FHIRCodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept route How drug should enter body */
		public ?FHIRCodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept method Technique for administering medication */
		public ?FHIRCodeableConcept $method = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDosageDoseAndRate> doseAndRate Amount of medication administered */
		public array $doseAndRate = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio maxDosePerPeriod Upper limit on medication per unit of time */
		public ?FHIRRatio $maxDosePerPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity maxDosePerAdministration Upper limit on medication per administration */
		public ?FHIRQuantity $maxDosePerAdministration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity maxDosePerLifetime Upper limit on medication per lifetime of the patient */
		public ?FHIRQuantity $maxDosePerLifetime = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
