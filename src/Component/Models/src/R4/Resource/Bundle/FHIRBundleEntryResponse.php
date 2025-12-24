<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates the results of processing the corresponding 'request' entry in the batch or transaction being responded to or what the results of an operation where when returning history.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.response', fhirVersion: 'R4')]
class FHIRBundleEntryResponse extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null status Status response code (text optional) */
        #[NotBlank]
        public FHIRString|string|null $status = null,
        /** @var FHIRUri|null location The location (if the operation returns a location) */
        public ?FHIRUri $location = null,
        /** @var FHIRString|string|null etag The Etag for the resource (if relevant) */
        public FHIRString|string|null $etag = null,
        /** @var FHIRInstant|null lastModified Server's date time modified */
        public ?FHIRInstant $lastModified = null,
        /** @var FHIRResource|null outcome OperationOutcome with hints and warnings (for batch/transaction) */
        public ?FHIRResource $outcome = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
