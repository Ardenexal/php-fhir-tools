<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ExtensionContextTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the types of resource or data type elements to which the extension can be applied.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.context', fhirVersion: 'R4')]
class StructureDefinitionContext extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ExtensionContextTypeType|null type fhirpath | element | extension */
        #[NotBlank]
        public ?ExtensionContextTypeType $type = null,
        /** @var StringPrimitive|string|null expression Where the extension can be used in instances */
        #[NotBlank]
        public StringPrimitive|string|null $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
