<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Dynamic values that will be evaluated to produce values for elements of the resulting resource. For example, if the dosage of a medication must be computed based on the patient's weight, a dynamic value would be used to specify an expression that calculated the weight, and the path on the request resource that would contain the result.
 */
#[FHIRBackboneElement(parentResource: 'ActivityDefinition', elementPath: 'ActivityDefinition.dynamicValue', fhirVersion: 'R4B')]
class FHIRActivityDefinitionDynamicValue extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null path The path to the element to be set dynamically */
        #[NotBlank]
        public \FHIRString|string|null $path = null,
        /** @var FHIRExpression|null expression An expression that provides the dynamic value for the customization */
        #[NotBlank]
        public ?\FHIRExpression $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
