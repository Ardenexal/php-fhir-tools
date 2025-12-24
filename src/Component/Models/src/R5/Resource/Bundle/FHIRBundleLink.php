<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A series of links that provide context to this bundle.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.link', fhirVersion: 'R5')]
class FHIRBundleLink extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRLinkRelationTypesType|null relation See http://www.iana.org/assignments/link-relations/link-relations.xhtml#link-relations-1 */
        #[NotBlank]
        public ?FHIRLinkRelationTypesType $relation = null,
        /** @var FHIRUri|null url Reference details for the link */
        #[NotBlank]
        public ?FHIRUri $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
