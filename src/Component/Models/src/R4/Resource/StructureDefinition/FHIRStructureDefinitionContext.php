<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the types of resource or data type elements to which the extension can be applied.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.context', fhirVersion: 'R4')]
class FHIRStructureDefinitionContext extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRExtensionContextTypeType|null type fhirpath | element | extension */
        #[NotBlank]
        public ?FHIRExtensionContextTypeType $type = null,
        /** @var FHIRString|string|null expression Where the extension can be used in instances */
        #[NotBlank]
        public FHIRString|string|null $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
