<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\GraphDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Potential target for the link.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link.target', fhirVersion: 'R4')]
class GraphDefinitionLinkTarget extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ResourceTypeType|null type Type of resource this link refers to */
        #[NotBlank]
        public ?ResourceTypeType $type = null,
        /** @var StringPrimitive|string|null params Criteria for reverse lookup */
        public StringPrimitive|string|null $params = null,
        /** @var CanonicalPrimitive|null profile Profile for the target resource */
        public ?CanonicalPrimitive $profile = null,
        /** @var array<GraphDefinitionLinkTargetCompartment> compartment Compartment Consistency Rules */
        public array $compartment = [],
        /** @var array<GraphDefinitionLink> link Additional links from target resource */
        public array $link = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
