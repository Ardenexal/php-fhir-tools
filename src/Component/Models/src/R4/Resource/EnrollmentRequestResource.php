<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EnrollmentRequest
 *
 * @description This resource provides the insurance enrollment details to the insurer regarding a specified coverage.
 */
#[FhirResource(
    type: 'EnrollmentRequest',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/EnrollmentRequest',
    fhirVersion: 'R4',
)]
class EnrollmentRequestResource extends DomainResourceResource
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
        /** @var DateTimePrimitive|null created Creation date */
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null insurer Target */
        public ?Reference $insurer = null,
        /** @var Reference|null provider Responsible practitioner */
        public ?Reference $provider = null,
        /** @var Reference|null candidate The subject to be enrolled */
        public ?Reference $candidate = null,
        /** @var Reference|null coverage Insurance information */
        public ?Reference $coverage = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
