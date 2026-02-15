<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A resource that is part of the implementation guide. Conformance resources (value set, structure definition, capability statements etc.) are obvious candidates for inclusion, but any kind of resource can be included as an example resource.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.resource', fhirVersion: 'R4')]
class ImplementationGuideDefinitionResource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null reference Location of the resource */
        #[NotBlank]
        public ?Reference $reference = null,
        /** @var array<FHIRVersionType> fhirVersion Versions this applies to (if different to IG) */
        public array $fhirVersion = [],
        /** @var StringPrimitive|string|null name Human Name for the resource */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Reason why included in guide */
        public StringPrimitive|string|null $description = null,
        /** @var bool|CanonicalPrimitive|null exampleX Is an example/What is this an example of? */
        public bool|CanonicalPrimitive|null $exampleX = null,
        /** @var IdPrimitive|null groupingId Grouping this is part of */
        public ?IdPrimitive $groupingId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
