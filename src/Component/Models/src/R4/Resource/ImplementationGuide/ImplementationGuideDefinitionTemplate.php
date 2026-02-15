<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A template for building resources.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.template', fhirVersion: 'R4')]
class ImplementationGuideDefinitionTemplate extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Type of template specified */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var StringPrimitive|string|null source The source location for the template */
        #[NotBlank]
        public StringPrimitive|string|null $source = null,
        /** @var StringPrimitive|string|null scope The scope in which the template applies */
        public StringPrimitive|string|null $scope = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
