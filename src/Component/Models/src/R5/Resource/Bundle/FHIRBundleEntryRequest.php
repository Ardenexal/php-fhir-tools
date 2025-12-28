<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional information about how this entry should be processed as part of a transaction or batch.  For history, it shows how the entry was processed to create the version contained in the entry.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.request', fhirVersion: 'R5')]
class FHIRBundleEntryRequest extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRHTTPVerbType|null method GET | HEAD | POST | PUT | DELETE | PATCH */
        #[NotBlank]
        public ?FHIRHTTPVerbType $method = null,
        /** @var FHIRUri|null url URL for HTTP equivalent of this entry */
        #[NotBlank]
        public ?FHIRUri $url = null,
        /** @var FHIRString|string|null ifNoneMatch For managing cache validation */
        public FHIRString|string|null $ifNoneMatch = null,
        /** @var FHIRInstant|null ifModifiedSince For managing cache currency */
        public ?FHIRInstant $ifModifiedSince = null,
        /** @var FHIRString|string|null ifMatch For managing update contention */
        public FHIRString|string|null $ifMatch = null,
        /** @var FHIRString|string|null ifNoneExist For conditional creates */
        public FHIRString|string|null $ifNoneExist = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
