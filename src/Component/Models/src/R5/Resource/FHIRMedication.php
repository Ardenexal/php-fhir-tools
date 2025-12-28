<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Medication
 *
 * @description This resource is primarily used for the identification and definition of a medication, including ingredients, for the purposes of prescribing, dispensing, and administering a medication as well as for making statements about medication use.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Medication', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Medication', fhirVersion: 'R5')]
class FHIRMedication extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this medication */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null code Codes that identify this medication */
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRMedicationStatusCodesType|null status active | inactive | entered-in-error */
        public ?\FHIRMedicationStatusCodesType $status = null,
        /** @var FHIRReference|null marketingAuthorizationHolder Organization that has authorization to market medication */
        public ?\FHIRReference $marketingAuthorizationHolder = null,
        /** @var FHIRCodeableConcept|null doseForm powder | tablets | capsule + */
        public ?\FHIRCodeableConcept $doseForm = null,
        /** @var FHIRQuantity|null totalVolume When the specified product code does not infer a package size, this is the specific amount of drug in the product */
        public ?\FHIRQuantity $totalVolume = null,
        /** @var array<FHIRMedicationIngredient> ingredient Active or inactive ingredient */
        public array $ingredient = [],
        /** @var FHIRMedicationBatch|null batch Details about packaged medications */
        public ?\FHIRMedicationBatch $batch = null,
        /** @var FHIRReference|null definition Knowledge about this medication */
        public ?\FHIRReference $definition = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
