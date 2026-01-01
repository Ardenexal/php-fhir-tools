<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRInventoryCountTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRInventoryReportStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/InventoryReport
 *
 * @description A report of inventory or stock items.
 */
#[FhirResource(
    type: 'InventoryReport',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/InventoryReport',
    fhirVersion: 'R5',
)]
class FHIRInventoryReport extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier for the report */
        public array $identifier = [],
        /** @var FHIRInventoryReportStatusType|null status draft | requested | active | entered-in-error */
        #[NotBlank]
        public ?FHIRInventoryReportStatusType $status = null,
        /** @var FHIRInventoryCountTypeType|null countType snapshot | difference */
        #[NotBlank]
        public ?FHIRInventoryCountTypeType $countType = null,
        /** @var FHIRCodeableConcept|null operationType addition | subtraction */
        public ?FHIRCodeableConcept $operationType = null,
        /** @var FHIRCodeableConcept|null operationTypeReason The reason for this count - regular count, ad-hoc count, new arrivals, etc */
        public ?FHIRCodeableConcept $operationTypeReason = null,
        /** @var FHIRDateTime|null reportedDateTime When the report has been submitted */
        #[NotBlank]
        public ?FHIRDateTime $reportedDateTime = null,
        /** @var FHIRReference|null reporter Who submits the report */
        public ?FHIRReference $reporter = null,
        /** @var FHIRPeriod|null reportingPeriod The period the report refers to */
        public ?FHIRPeriod $reportingPeriod = null,
        /** @var array<FHIRInventoryReportInventoryListing> inventoryListing An inventory listing section (grouped by any of the attributes) */
        public array $inventoryListing = [],
        /** @var array<FHIRAnnotation> note A note associated with the InventoryReport */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
