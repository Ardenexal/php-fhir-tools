<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization\MedicinalProductAuthorizationJurisdictionalAuthorization;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization\MedicinalProductAuthorizationProcedure;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductAuthorization
 *
 * @description The regulatory authorization of a medicinal product.
 */
#[FhirResource(
    type: 'MedicinalProductAuthorization',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductAuthorization',
    fhirVersion: 'R4',
)]
class MedicinalProductAuthorizationResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business identifier for the marketing authorization, as assigned by a regulator */
        public array $identifier = [],
        /** @var Reference|null subject The medicinal product that is being authorized */
        public ?Reference $subject = null,
        /** @var array<CodeableConcept> country The country in which the marketing authorization has been granted */
        public array $country = [],
        /** @var array<CodeableConcept> jurisdiction Jurisdiction within a country */
        public array $jurisdiction = [],
        /** @var CodeableConcept|null status The status of the marketing authorization */
        public ?CodeableConcept $status = null,
        /** @var DateTimePrimitive|null statusDate The date at which the given status has become applicable */
        public ?DateTimePrimitive $statusDate = null,
        /** @var DateTimePrimitive|null restoreDate The date when a suspended the marketing or the marketing authorization of the product is anticipated to be restored */
        public ?DateTimePrimitive $restoreDate = null,
        /** @var Period|null validityPeriod The beginning of the time period in which the marketing authorization is in the specific status shall be specified A complete date consisting of day, month and year shall be specified using the ISO 8601 date format */
        public ?Period $validityPeriod = null,
        /** @var Period|null dataExclusivityPeriod A period of time after authorization before generic product applicatiosn can be submitted */
        public ?Period $dataExclusivityPeriod = null,
        /** @var DateTimePrimitive|null dateOfFirstAuthorization The date when the first authorization was granted by a Medicines Regulatory Agency */
        public ?DateTimePrimitive $dateOfFirstAuthorization = null,
        /** @var DateTimePrimitive|null internationalBirthDate Date of first marketing authorization for a company's new medicinal product in any country in the World */
        public ?DateTimePrimitive $internationalBirthDate = null,
        /** @var CodeableConcept|null legalBasis The legal framework against which this authorization is granted */
        public ?CodeableConcept $legalBasis = null,
        /** @var array<MedicinalProductAuthorizationJurisdictionalAuthorization> jurisdictionalAuthorization Authorization in areas within a country */
        public array $jurisdictionalAuthorization = [],
        /** @var Reference|null holder Marketing Authorization Holder */
        public ?Reference $holder = null,
        /** @var Reference|null regulator Medicines Regulatory Agency */
        public ?Reference $regulator = null,
        /** @var MedicinalProductAuthorizationProcedure|null procedure The regulatory procedure for granting or amending a marketing authorization */
        public ?MedicinalProductAuthorizationProcedure $procedure = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
