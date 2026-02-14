<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates the results of processing the corresponding 'request' entry in the batch or transaction being responded to or what the results of an operation where when returning history.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.response', fhirVersion: 'R4')]
class BundleEntryResponse extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null status Status response code (text optional) */
        #[NotBlank]
        public StringPrimitive|string|null $status = null,
        /** @var UriPrimitive|null location The location (if the operation returns a location) */
        public ?UriPrimitive $location = null,
        /** @var StringPrimitive|string|null etag The Etag for the resource (if relevant) */
        public StringPrimitive|string|null $etag = null,
        /** @var InstantPrimitive|null lastModified Server's date time modified */
        public ?InstantPrimitive $lastModified = null,
        /** @var ResourceResource|null outcome OperationOutcome with hints and warnings (for batch/transaction) */
        public ?ResourceResource $outcome = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
