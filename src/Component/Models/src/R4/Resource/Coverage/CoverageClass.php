<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Coverage;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A suite of underwriter specific classifiers.
 */
#[FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.class', fhirVersion: 'R4')]
class CoverageClass extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of class such as 'group' or 'plan' */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var StringPrimitive|string|null value Value associated with the type */
        #[NotBlank]
        public StringPrimitive|string|null $value = null,
        /** @var StringPrimitive|string|null name Human readable description of the type and value */
        public StringPrimitive|string|null $name = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
