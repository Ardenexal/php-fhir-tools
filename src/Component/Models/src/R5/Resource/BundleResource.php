<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BundleTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UnsignedIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleLink;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Bundle
 *
 * @description A container for a collection of resources.
 */
#[FhirResource(type: 'Bundle', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Bundle', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'bdl-1',
    severity: 'error',
    expression: 'total.empty() or (type = \'searchset\') or (type = \'history\')',
    human: 'total only when a search or history',
)]
#[FHIRPathInvariant(
    key: 'bdl-2',
    severity: 'error',
    expression: '(type = \'searchset\') or entry.search.empty()',
    human: 'entry.search only when a search',
)]
#[FHIRPathInvariant(
    key: 'bdl-7',
    severity: 'error',
    expression: '(type = \'history\') or entry.where(fullUrl.exists()).select(fullUrl&iif(resource.meta.versionId.exists(), resource.meta.versionId, \'\')).isDistinct()',
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
#[FHIRPathInvariant(
    key: 'bdl-13',
    severity: 'error',
    expression: 'type = \'subscription-notification\' implies entry.first().resource.is(SubscriptionStatus)',
    human: 'A subscription-notification must have a SubscriptionStatus as the first resource',
)]
#[FHIRPathInvariant(
    key: 'bdl-14',
    severity: 'error',
    expression: 'type = \'history\' implies entry.request.method != \'PATCH\'',
    human: 'entry.request.method PATCH not allowed for history',
)]
#[FHIRPathInvariant(
    key: 'bdl-15',
    severity: 'error',
    expression: 'type=\'transaction\' or type=\'transaction-response\' or type=\'batch\' or type=\'batch-response\' or entry.all(fullUrl.exists() or request.method=\'POST\')',
    human: 'Bundle resources where type is not transaction, transaction-response, batch, or batch-response or when the request is a POST SHALL have Bundle.entry.fullUrl populated',
)]
#[FHIRPathInvariant(
    key: 'bdl-16',
    severity: 'error',
    expression: 'issues.exists() implies (issues.issue.severity = \'information\' or issues.issue.severity = \'warning\')',
    human: 'Issue.severity for all issues within the OperationOutcome must be either \'information\' or \'warning\'.',
)]
#[FHIRPathInvariant(
    key: 'bdl-17',
    severity: 'error',
    expression: 'type = \'document\' implies issues.empty()',
    human: 'Use and meaning of issues for documents has not been validated because the content will not be rendered in the document.',
)]
#[FHIRPathInvariant(
    key: 'bdl-18',
    severity: 'error',
    expression: 'type = \'searchset\' implies link.where(relation = \'self\' and url.exists()).exists()',
    human: 'Self link is required for searchsets.',
)]
#[FHIRPathInvariant(
    key: 'bdl-3a',
    severity: 'error',
    expression: 'type in (\'document\' | \'message\' | \'searchset\' | \'collection\') implies entry.all(resource.exists() and request.empty() and response.empty())',
    human: 'For collections of type document, message, searchset or collection, all entries must contain resources, and not have request or response elements',
)]
#[FHIRPathInvariant(
    key: 'bdl-3b',
    severity: 'error',
    expression: 'type = \'history\' implies entry.all(request.exists() and response.exists() and ((request.method in (\'POST\' | \'PATCH\' | \'PUT\')) = resource.exists()))',
    human: 'For collections of type history, all entries must contain request or response elements, and resources if the method is POST, PUT or PATCH',
)]
#[FHIRPathInvariant(
    key: 'bdl-3c',
    severity: 'error',
    expression: 'type in (\'transaction\' | \'batch\') implies entry.all(request.method.exists() and ((request.method in (\'POST\' | \'PATCH\' | \'PUT\')) = resource.exists()))',
    human: 'For collections of type transaction or batch, all entries must contain request elements, and resources if the method is POST, PUT or PATCH',
)]
#[FHIRPathInvariant(
    key: 'bdl-3d',
    severity: 'error',
    expression: 'type in (\'transaction-response\' | \'batch-response\') implies entry.all(response.exists())',
    human: 'For collections of type transaction-response or batch-response, all entries must contain response elements',
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
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
        /** @var Identifier|null identifier Persistent identifier for the bundle */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var BundleTypeType|null type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection | subscription-notification */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/bundle-type|5.0.0', strength: 'required')]
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
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleLink',
        )]
        public array $link = [],
        /** @var array<BundleEntry> entry Entry in the bundle - will have a resource or information */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleEntry',
        )]
        public array $entry = [],
        /** @var Signature|null signature Digital Signature */
        #[FhirProperty(fhirType: 'Signature', propertyKind: 'complex')]
        public ?Signature $signature = null,
        /** @var ResourceResource|null issues Issues with the Bundle */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource')]
        public ?ResourceResource $issues = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
