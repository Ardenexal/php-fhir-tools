<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Dosage
 *
 * @description Indicates how the medication is/was taken or should be taken by the patient.
 */
#[FHIRBackboneElement(parentResource: 'Dosage', elementPath: 'Dosage', fhirVersion: 'R4')]
class Dosage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var int|null sequence The order of the dosage instructions */
        public ?int $sequence = null,
        /** @var StringPrimitive|string|null text Free text dosage instructions e.g. SIG */
        public StringPrimitive|string|null $text = null,
        /** @var array<CodeableConcept> additionalInstruction Supplemental instruction or warnings to the patient - e.g. "with meals", "may cause drowsiness" */
        public array $additionalInstruction = [],
        /** @var StringPrimitive|string|null patientInstruction Patient or consumer oriented instructions */
        public StringPrimitive|string|null $patientInstruction = null,
        /** @var Timing|null timing When medication should be administered */
        public ?Timing $timing = null,
        /** @var bool|CodeableConcept|null asNeededX Take "as needed" (for x) */
        public bool|CodeableConcept|null $asNeededX = null,
        /** @var CodeableConcept|null site Body site to administer to */
        public ?CodeableConcept $site = null,
        /** @var CodeableConcept|null route How drug should enter body */
        public ?CodeableConcept $route = null,
        /** @var CodeableConcept|null method Technique for administering medication */
        public ?CodeableConcept $method = null,
        /** @var array<DosageDoseAndRate> doseAndRate Amount of medication administered */
        public array $doseAndRate = [],
        /** @var Ratio|null maxDosePerPeriod Upper limit on medication per unit of time */
        public ?Ratio $maxDosePerPeriod = null,
        /** @var Quantity|null maxDosePerAdministration Upper limit on medication per administration */
        public ?Quantity $maxDosePerAdministration = null,
        /** @var Quantity|null maxDosePerLifetime Upper limit on medication per lifetime of the patient */
        public ?Quantity $maxDosePerLifetime = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
