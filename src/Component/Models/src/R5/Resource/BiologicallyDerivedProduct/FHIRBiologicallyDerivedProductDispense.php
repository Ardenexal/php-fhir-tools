<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBiologicallyDerivedProductDispenseCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProductDispense
 *
 * @description This resource reflects an instance of a biologically derived product dispense. The supply or dispense of a biologically derived product from the supply organization or department (e.g. hospital transfusion laboratory) to the clinical team responsible for clinical application.
 */
#[FhirResource(
    type: 'BiologicallyDerivedProductDispense',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProductDispense',
    fhirVersion: 'R5',
)]
class FHIRBiologicallyDerivedProductDispense extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business identifier for this dispense */
        public array $identifier = [],
        /** @var array<FHIRReference> basedOn The order or request that this dispense is fulfilling */
        public array $basedOn = [],
        /** @var array<FHIRReference> partOf Short description */
        public array $partOf = [],
        /** @var FHIRBiologicallyDerivedProductDispenseCodesType|null status preparation | in-progress | allocated | issued | unfulfilled | returned | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRBiologicallyDerivedProductDispenseCodesType $status = null,
        /** @var FHIRCodeableConcept|null originRelationshipType Relationship between the donor and intended recipient */
        public ?FHIRCodeableConcept $originRelationshipType = null,
        /** @var FHIRReference|null product The BiologicallyDerivedProduct that is dispensed */
        #[NotBlank]
        public ?FHIRReference $product = null,
        /** @var FHIRReference|null patient The intended recipient of the dispensed product */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRCodeableConcept|null matchStatus Indicates the type of matching associated with the dispense */
        public ?FHIRCodeableConcept $matchStatus = null,
        /** @var array<FHIRBiologicallyDerivedProductDispensePerformer> performer Indicates who or what performed an action */
        public array $performer = [],
        /** @var FHIRReference|null location Where the dispense occurred */
        public ?FHIRReference $location = null,
        /** @var FHIRQuantity|null quantity Amount dispensed */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRDateTime|null preparedDate When product was selected/matched */
        public ?FHIRDateTime $preparedDate = null,
        /** @var FHIRDateTime|null whenHandedOver When the product was dispatched */
        public ?FHIRDateTime $whenHandedOver = null,
        /** @var FHIRReference|null destination Where the product was dispatched to */
        public ?FHIRReference $destination = null,
        /** @var array<FHIRAnnotation> note Additional notes */
        public array $note = [],
        /** @var FHIRString|string|null usageInstruction Specific instructions for use */
        public FHIRString|string|null $usageInstruction = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
