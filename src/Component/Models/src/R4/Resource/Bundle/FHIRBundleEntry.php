<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @description An entry in a bundle resource - will either contain a resource or information about a resource (transactions and history only).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry', fhirVersion: 'R4')]
class FHIRBundleEntry extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRBundleLink> link Links related to this entry */
        public array $link = [],
        /** @var FHIRUri|null fullUrl URI for resource (Absolute URL server address or URI for UUID/OID) */
        public ?FHIRUri $fullUrl = null,
        /** @var FHIRResource|null resource A resource in the bundle */
        public ?FHIRResource $resource = null,
        /** @var FHIRBundleEntrySearch|null search Search related information */
        public ?FHIRBundleEntrySearch $search = null,
        /** @var FHIRBundleEntryRequest|null request Additional execution information (transaction/batch/history) */
        public ?FHIRBundleEntryRequest $request = null,
        /** @var FHIRBundleEntryResponse|null response Results of execution (transaction/batch/history) */
        public ?FHIRBundleEntryResponse $response = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
