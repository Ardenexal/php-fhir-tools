<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\AccountStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Account\AccountCoverage;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Account\AccountGuarantor;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Account
 *
 * @description A financial tool for tracking value accrued for a particular purpose.  In the healthcare field, used to track charges for a patient, cost centers, etc.
 */
#[FhirResource(type: 'Account', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Account', fhirVersion: 'R4')]
class AccountResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Account number */
        public array $identifier = [],
        /** @var AccountStatusType|null status active | inactive | entered-in-error | on-hold | unknown */
        #[NotBlank]
        public ?AccountStatusType $status = null,
        /** @var CodeableConcept|null type E.g. patient, expense, depreciation */
        public ?CodeableConcept $type = null,
        /** @var StringPrimitive|string|null name Human-readable label */
        public StringPrimitive|string|null $name = null,
        /** @var array<Reference> subject The entity that caused the expenses */
        public array $subject = [],
        /** @var Period|null servicePeriod Transaction window */
        public ?Period $servicePeriod = null,
        /** @var array<AccountCoverage> coverage The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account */
        public array $coverage = [],
        /** @var Reference|null owner Entity managing the Account */
        public ?Reference $owner = null,
        /** @var StringPrimitive|string|null description Explanation of purpose/use */
        public StringPrimitive|string|null $description = null,
        /** @var array<AccountGuarantor> guarantor The parties ultimately responsible for balancing the Account */
        public array $guarantor = [],
        /** @var Reference|null partOf Reference to a parent Account */
        public ?Reference $partOf = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
