<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Bundle\BundleLink;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Bundle
 *
 * @description A container for a collection of resources.
 */
#[FhirResource(type: 'Bundle', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Bundle', fhirVersion: 'R4B')]
class BundleResource extends ResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Identifier|null identifier Persistent identifier for the bundle */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var BundleTypeType|null type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?BundleTypeType $type = null,
        /** @var InstantPrimitive|null timestamp When the bundle was assembled */
        #[FhirProperty(fhirType: 'instant', propertyKind: 'primitive')]
        public ?InstantPrimitive $timestamp = null,
        /** @var UnsignedIntPrimitive|null total If search, the total number of matches */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public ?UnsignedIntPrimitive $total = null,
        /** @var array<BundleLink> link Links related to this Bundle */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Bundle\BundleLink',
        )]
        public array $link = [],
        /** @var array<BundleEntry> entry Entry in the bundle - will have a resource or information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Bundle\BundleEntry',
        )]
        public array $entry = [],
        /** @var Signature|null signature Digital Signature */
        #[FhirProperty(fhirType: 'Signature', propertyKind: 'complex')]
        public ?Signature $signature = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
