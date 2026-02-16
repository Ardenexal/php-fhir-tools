<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MedicationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Medication\MedicationBatch;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Medication\MedicationIngredient;

/**
 * @author Health Level Seven International (Pharmacy)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Medication
 *
 * @description This resource is primarily used for the identification and definition of a medication for the purposes of prescribing, dispensing, and administering a medication as well as for making statements about medication use.
 */
#[FhirResource(type: 'Medication', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Medication', fhirVersion: 'R4')]
class MedicationResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business identifier for this medication */
        public array $identifier = [],
        /** @var CodeableConcept|null code Codes that identify this medication */
        public ?CodeableConcept $code = null,
        /** @var MedicationStatusCodesType|null status active | inactive | entered-in-error */
        public ?MedicationStatusCodesType $status = null,
        /** @var Reference|null manufacturer Manufacturer of the item */
        public ?Reference $manufacturer = null,
        /** @var CodeableConcept|null form powder | tablets | capsule + */
        public ?CodeableConcept $form = null,
        /** @var Ratio|null amount Amount of drug in package */
        public ?Ratio $amount = null,
        /** @var array<MedicationIngredient> ingredient Active or inactive ingredient */
        public array $ingredient = [],
        /** @var MedicationBatch|null batch Details about packaged medications */
        public ?MedicationBatch $batch = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
