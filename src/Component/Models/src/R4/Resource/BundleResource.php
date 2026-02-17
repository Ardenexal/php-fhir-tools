<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleLink;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Bundle
 *
 * @description A container for a collection of resources.
 */
#[FhirResource(type: 'Bundle', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Bundle', fhirVersion: 'R4')]
class BundleResource extends ResourceResource
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
        /** @var Identifier|null identifier Persistent identifier for the bundle */
        public ?Identifier $identifier = null,
        /** @var BundleTypeType|null type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection */
        #[NotBlank]
        public ?BundleTypeType $type = null,
        /** @var InstantPrimitive|null timestamp When the bundle was assembled */
        public ?InstantPrimitive $timestamp = null,
        /** @var UnsignedIntPrimitive|null total If search, the total number of matches */
        public ?UnsignedIntPrimitive $total = null,
        /** @var array<BundleLink> link Links related to this Bundle */
        public array $link = [],
        /** @var array<BundleEntry> entry Entry in the bundle - will have a resource or information */
        public array $entry = [],
        /** @var Signature|null signature Digital Signature */
        public ?Signature $signature = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
