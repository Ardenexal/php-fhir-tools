<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource;

/**
 * @description An entry in a bundle resource - will either contain a resource or information about a resource (transactions and history only).
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry', fhirVersion: 'R4')]
class BundleEntry extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<BundleLink> link Links related to this entry */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleLink',
        )]
        public array $link = [],
        /** @var UriPrimitive|null fullUrl URI for resource (Absolute URL server address or URI for UUID/OID) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $fullUrl = null,
        /** @var ResourceResource|null resource A resource in the bundle */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource')]
        public ?ResourceResource $resource = null,
        /** @var BundleEntrySearch|null search Search related information */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?BundleEntrySearch $search = null,
        /** @var BundleEntryRequest|null request Additional execution information (transaction/batch/history) */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?BundleEntryRequest $request = null,
        /** @var BundleEntryResponse|null response Results of execution (transaction/batch/history) */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?BundleEntryResponse $response = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
