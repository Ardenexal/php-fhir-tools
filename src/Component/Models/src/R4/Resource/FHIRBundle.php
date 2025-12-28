<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Bundle
 *
 * @description A container for a collection of resources.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Bundle', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Bundle', fhirVersion: 'R4')]
class FHIRBundle extends FHIRResource
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
        /** @var FHIRIdentifier|null identifier Persistent identifier for the bundle */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRBundleTypeType|null type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection */
        #[NotBlank]
        public ?FHIRBundleTypeType $type = null,
        /** @var FHIRInstant|null timestamp When the bundle was assembled */
        public ?FHIRInstant $timestamp = null,
        /** @var FHIRUnsignedInt|null total If search, the total number of matches */
        public ?FHIRUnsignedInt $total = null,
        /** @var array<FHIRBundleLink> link Links related to this Bundle */
        public array $link = [],
        /** @var array<FHIRBundleEntry> entry Entry in the bundle - will have a resource or information */
        public array $entry = [],
        /** @var FHIRSignature|null signature Digital Signature */
        public ?FHIRSignature $signature = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
