<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HTTPVerbType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional information about how this entry should be processed as part of a transaction or batch.  For history, it shows how the entry was processed to create the version contained in the entry.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.request', fhirVersion: 'R4')]
class BundleEntryRequest extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var HTTPVerbType|null method GET | HEAD | POST | PUT | DELETE | PATCH */
        #[NotBlank]
        public ?HTTPVerbType $method = null,
        /** @var UriPrimitive|null url URL for HTTP equivalent of this entry */
        #[NotBlank]
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null ifNoneMatch For managing cache currency */
        public StringPrimitive|string|null $ifNoneMatch = null,
        /** @var InstantPrimitive|null ifModifiedSince For managing cache currency */
        public ?InstantPrimitive $ifModifiedSince = null,
        /** @var StringPrimitive|string|null ifMatch For managing update contention */
        public StringPrimitive|string|null $ifMatch = null,
        /** @var StringPrimitive|string|null ifNoneExist For conditional creates */
        public StringPrimitive|string|null $ifNoneExist = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
