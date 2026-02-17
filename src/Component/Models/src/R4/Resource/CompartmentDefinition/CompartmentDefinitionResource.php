<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CompartmentDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about how a resource is related to the compartment.
 */
#[FHIRBackboneElement(parentResource: 'CompartmentDefinition', elementPath: 'CompartmentDefinition.resource', fhirVersion: 'R4')]
class CompartmentDefinitionResource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ResourceTypeType|null code Name of resource type */
        #[NotBlank]
        public ?ResourceTypeType $code = null,
        /** @var array<StringPrimitive|string> param Search Parameter Name, or chained parameters */
        public array $param = [],
        /** @var StringPrimitive|string|null documentation Additional documentation about the resource and compartment */
        public StringPrimitive|string|null $documentation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
