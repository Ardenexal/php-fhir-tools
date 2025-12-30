<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMedicationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Medication
 *
 * @description This resource is primarily used for the identification and definition of a medication for the purposes of prescribing, dispensing, and administering a medication as well as for making statements about medication use.
 */
#[FhirResource(type: 'Medication', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Medication', fhirVersion: 'R4B')]
class FHIRMedication extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this medication */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null code Codes that identify this medication */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRMedicationStatusCodesType|null status active | inactive | entered-in-error */
        public ?FHIRMedicationStatusCodesType $status = null,
        /** @var FHIRReference|null manufacturer Manufacturer of the item */
        public ?FHIRReference $manufacturer = null,
        /** @var FHIRCodeableConcept|null form powder | tablets | capsule + */
        public ?FHIRCodeableConcept $form = null,
        /** @var FHIRRatio|null amount Amount of drug in package */
        public ?FHIRRatio $amount = null,
        /** @var array<FHIRMedicationIngredient> ingredient Active or inactive ingredient */
        public array $ingredient = [],
        /** @var FHIRMedicationBatch|null batch Details about packaged medications */
        public ?FHIRMedicationBatch $batch = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
