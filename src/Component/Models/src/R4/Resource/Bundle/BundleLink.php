<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A series of links that provide context to this bundle.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.link', fhirVersion: 'R4')]
class BundleLink extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null relation See http://www.iana.org/assignments/link-relations/link-relations.xhtml#link-relations-1 */
        #[NotBlank]
        public StringPrimitive|string|null $relation = null,
        /** @var UriPrimitive|null url Reference details for the link */
        #[NotBlank]
        public ?UriPrimitive $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
