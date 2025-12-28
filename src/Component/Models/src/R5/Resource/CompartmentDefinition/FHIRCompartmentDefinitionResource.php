<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about how a resource is related to the compartment.
 */
#[FHIRBackboneElement(parentResource: 'CompartmentDefinition', elementPath: 'CompartmentDefinition.resource', fhirVersion: 'R5')]
class FHIRCompartmentDefinitionResource extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRResourceTypeType|null code Name of resource type */
        #[NotBlank]
        public ?\FHIRResourceTypeType $code = null,
        /** @var array<FHIRString|string> param Search Parameter Name, or chained parameters */
        public array $param = [],
        /** @var FHIRString|string|null documentation Additional documentation about the resource and compartment */
        public \FHIRString|string|null $documentation = null,
        /** @var FHIRUri|null startParam Search Param for interpreting $everything.start */
        public ?\FHIRUri $startParam = null,
        /** @var FHIRUri|null endParam Search Param for interpreting $everything.end */
        public ?\FHIRUri $endParam = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
