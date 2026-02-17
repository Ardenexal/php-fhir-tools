<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the resource (or resources) that are being addressed by the event.  For example, the Encounter for an admit message or two Account records for a merge.
 */
#[FHIRBackboneElement(parentResource: 'MessageDefinition', elementPath: 'MessageDefinition.focus', fhirVersion: 'R4')]
class MessageDefinitionFocus extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ResourceTypeType|null code Type of resource */
        #[NotBlank]
        public ?ResourceTypeType $code = null,
        /** @var CanonicalPrimitive|null profile Profile that must be adhered to by focus */
        public ?CanonicalPrimitive $profile = null,
        /** @var UnsignedIntPrimitive|null min Minimum number of focuses of this type */
        #[NotBlank]
        public ?UnsignedIntPrimitive $min = null,
        /** @var StringPrimitive|string|null max Maximum number of focuses of this type */
        public StringPrimitive|string|null $max = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
