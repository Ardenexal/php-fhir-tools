<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
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
#[FHIRPathInvariant(
    key: 'bdl-1',
    severity: 'error',
    expression: 'total.empty() or (type = \'searchset\') or (type = \'history\')',
    human: 'total only when a search or history',
)]
#[FHIRPathInvariant(
    key: 'bdl-2',
    severity: 'error',
    expression: 'entry.search.empty() or (type = \'searchset\')',
    human: 'entry.search only when a search',
)]
#[FHIRPathInvariant(
    key: 'bdl-3',
    severity: 'error',
    expression: 'entry.all(request.exists() = (%resource.type = \'batch\' or %resource.type = \'transaction\' or %resource.type = \'history\'))',
    human: 'entry.request mandatory for batch/transaction/history, otherwise prohibited',
)]
#[FHIRPathInvariant(
    key: 'bdl-4',
    severity: 'error',
    expression: 'entry.all(response.exists() = (%resource.type = \'batch-response\' or %resource.type = \'transaction-response\' or %resource.type = \'history\'))',
    human: 'entry.response mandatory for batch-response/transaction-response/history, otherwise prohibited',
)]
#[FHIRPathInvariant(
    key: 'bdl-7',
    severity: 'error',
    expression: '(type = \'history\') or entry.where(fullUrl.exists()).select(fullUrl&resource.meta.versionId).isDistinct()',
    human: 'FullUrl must be unique in a bundle, or else entries with the same fullUrl must have different meta.versionId (except in history bundles)',
)]
#[FHIRPathInvariant(
    key: 'bdl-9',
    severity: 'error',
    expression: 'type = \'document\' implies (identifier.system.exists() and identifier.value.exists())',
    human: 'A document must have an identifier with a system and a value',
)]
#[FHIRPathInvariant(
    key: 'bdl-10',
    severity: 'error',
    expression: 'type = \'document\' implies (timestamp.hasValue())',
    human: 'A document must have a date',
)]
#[FHIRPathInvariant(
    key: 'bdl-11',
    severity: 'error',
    expression: 'type = \'document\' implies entry.first().resource.is(Composition)',
    human: 'A document must have a Composition as the first resource',
)]
#[FHIRPathInvariant(
    key: 'bdl-12',
    severity: 'error',
    expression: 'type = \'message\' implies entry.first().resource.is(MessageHeader)',
    human: 'A message must have a MessageHeader as the first resource',
)]
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
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/languages',
            strength: 'preferred',
            maxValueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages',
        )]
        public ?string $language = null,
        /** @var Identifier|null identifier Persistent identifier for the bundle */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var BundleTypeType|null type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/bundle-type|4.3.0', strength: 'required')]
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
