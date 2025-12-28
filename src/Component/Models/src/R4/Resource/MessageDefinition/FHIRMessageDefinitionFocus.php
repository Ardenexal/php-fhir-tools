<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the resource (or resources) that are being addressed by the event.  For example, the Encounter for an admit message or two Account records for a merge.
 */
#[FHIRBackboneElement(parentResource: 'MessageDefinition', elementPath: 'MessageDefinition.focus', fhirVersion: 'R4')]
class FHIRMessageDefinitionFocus extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRResourceTypeType|null code Type of resource */
        #[NotBlank]
        public ?\FHIRResourceTypeType $code = null,
        /** @var FHIRCanonical|null profile Profile that must be adhered to by focus */
        public ?\FHIRCanonical $profile = null,
        /** @var FHIRUnsignedInt|null min Minimum number of focuses of this type */
        #[NotBlank]
        public ?\FHIRUnsignedInt $min = null,
        /** @var FHIRString|string|null max Maximum number of focuses of this type */
        public \FHIRString|string|null $max = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
