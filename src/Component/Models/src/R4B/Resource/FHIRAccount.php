<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Account
 *
 * @description A financial tool for tracking value accrued for a particular purpose.  In the healthcare field, used to track charges for a patient, cost centers, etc.
 */
#[FhirResource(type: 'Account', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Account', fhirVersion: 'R4B')]
class FHIRAccount extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Account number */
        public array $identifier = [],
        /** @var FHIRAccountStatusType|null status active | inactive | entered-in-error | on-hold | unknown */
        #[NotBlank]
        public ?FHIRAccountStatusType $status = null,
        /** @var FHIRCodeableConcept|null type E.g. patient, expense, depreciation */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null name Human-readable label */
        public FHIRString|string|null $name = null,
        /** @var array<FHIRReference> subject The entity that caused the expenses */
        public array $subject = [],
        /** @var FHIRPeriod|null servicePeriod Transaction window */
        public ?FHIRPeriod $servicePeriod = null,
        /** @var array<FHIRAccountCoverage> coverage The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account */
        public array $coverage = [],
        /** @var FHIRReference|null owner Entity managing the Account */
        public ?FHIRReference $owner = null,
        /** @var FHIRString|string|null description Explanation of purpose/use */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRAccountGuarantor> guarantor The parties ultimately responsible for balancing the Account */
        public array $guarantor = [],
        /** @var FHIRReference|null partOf Reference to a parent Account */
        public ?FHIRReference $partOf = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
