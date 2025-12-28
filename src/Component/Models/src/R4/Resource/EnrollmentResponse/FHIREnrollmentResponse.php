<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EnrollmentResponse
 *
 * @description This resource provides enrollment and plan details from the processing of an EnrollmentRequest resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'EnrollmentResponse',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/EnrollmentResponse',
    fhirVersion: 'R4',
)]
class FHIREnrollmentResponse extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier */
        public array $identifier = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        public ?\FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRReference|null request Claim reference */
        public ?\FHIRReference $request = null,
        /** @var FHIRClaimProcessingCodesType|null outcome queued | complete | error | partial */
        public ?\FHIRClaimProcessingCodesType $outcome = null,
        /** @var FHIRString|string|null disposition Disposition Message */
        public \FHIRString|string|null $disposition = null,
        /** @var FHIRDateTime|null created Creation date */
        public ?\FHIRDateTime $created = null,
        /** @var FHIRReference|null organization Insurer */
        public ?\FHIRReference $organization = null,
        /** @var FHIRReference|null requestProvider Responsible practitioner */
        public ?\FHIRReference $requestProvider = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
