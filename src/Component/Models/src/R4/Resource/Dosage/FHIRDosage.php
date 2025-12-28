<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Dosage
 *
 * @description Indicates how the medication is/was taken or should be taken by the patient.
 */
#[FHIRBackboneElement(parentResource: 'Dosage', elementPath: 'Dosage', fhirVersion: 'R4')]
class FHIRDosage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null sequence The order of the dosage instructions */
        public ?FHIRInteger $sequence = null,
        /** @var FHIRString|string|null text Free text dosage instructions e.g. SIG */
        public FHIRString|string|null $text = null,
        /** @var array<FHIRCodeableConcept> additionalInstruction Supplemental instruction or warnings to the patient - e.g. "with meals", "may cause drowsiness" */
        public array $additionalInstruction = [],
        /** @var FHIRString|string|null patientInstruction Patient or consumer oriented instructions */
        public FHIRString|string|null $patientInstruction = null,
        /** @var FHIRTiming|null timing When medication should be administered */
        public ?FHIRTiming $timing = null,
        /** @var FHIRBoolean|FHIRCodeableConcept|null asNeededX Take "as needed" (for x) */
        public FHIRBoolean|FHIRCodeableConcept|null $asNeededX = null,
        /** @var FHIRCodeableConcept|null site Body site to administer to */
        public ?FHIRCodeableConcept $site = null,
        /** @var FHIRCodeableConcept|null route How drug should enter body */
        public ?FHIRCodeableConcept $route = null,
        /** @var FHIRCodeableConcept|null method Technique for administering medication */
        public ?FHIRCodeableConcept $method = null,
        /** @var array<FHIRDosageDoseAndRate> doseAndRate Amount of medication administered */
        public array $doseAndRate = [],
        /** @var FHIRRatio|null maxDosePerPeriod Upper limit on medication per unit of time */
        public ?FHIRRatio $maxDosePerPeriod = null,
        /** @var FHIRQuantity|null maxDosePerAdministration Upper limit on medication per administration */
        public ?FHIRQuantity $maxDosePerAdministration = null,
        /** @var FHIRQuantity|null maxDosePerLifetime Upper limit on medication per lifetime of the patient */
        public ?FHIRQuantity $maxDosePerLifetime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
