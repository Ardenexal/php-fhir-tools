<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CodeSystem;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PropertyTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A property defines an additional slot through which additional information can be provided about a concept.
 */
#[FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.property', fhirVersion: 'R4')]
class CodeSystemProperty extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Identifies the property on the concepts, and when referred to in operations */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var UriPrimitive|null uri Formal identifier for the property */
        public ?UriPrimitive $uri = null,
        /** @var StringPrimitive|string|null description Why the property is defined, and/or what it conveys */
        public StringPrimitive|string|null $description = null,
        /** @var PropertyTypeType|null type code | Coding | string | integer | boolean | dateTime | decimal */
        #[NotBlank]
        public ?PropertyTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
