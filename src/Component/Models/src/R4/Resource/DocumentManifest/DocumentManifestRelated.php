<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DocumentManifest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Related identifiers or resources associated with the DocumentManifest.
 */
#[FHIRBackboneElement(parentResource: 'DocumentManifest', elementPath: 'DocumentManifest.related', fhirVersion: 'R4')]
class DocumentManifestRelated extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Identifiers of things that are related */
        public ?Identifier $identifier = null,
        /** @var Reference|null ref Related Resource */
        public ?Reference $ref = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
