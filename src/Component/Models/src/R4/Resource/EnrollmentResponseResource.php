<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EnrollmentResponse
 *
 * @description This resource provides enrollment and plan details from the processing of an EnrollmentRequest resource.
 */
#[FhirResource(
    type: 'EnrollmentResponse',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/EnrollmentResponse',
    fhirVersion: 'R4',
)]
class EnrollmentResponseResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var Reference|null request Claim reference */
        public ?Reference $request = null,
        /** @var ClaimProcessingCodesType|null outcome queued | complete | error | partial */
        public ?ClaimProcessingCodesType $outcome = null,
        /** @var StringPrimitive|string|null disposition Disposition Message */
        public StringPrimitive|string|null $disposition = null,
        /** @var DateTimePrimitive|null created Creation date */
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null organization Insurer */
        public ?Reference $organization = null,
        /** @var Reference|null requestProvider Responsible practitioner */
        public ?Reference $requestProvider = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
