<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\GraphDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CompartmentTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GraphCompartmentRuleType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GraphCompartmentUseType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Compartment Consistency Rules.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link.target.compartment', fhirVersion: 'R4')]
class GraphDefinitionLinkTargetCompartment extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var GraphCompartmentUseType|null use condition | requirement */
        #[NotBlank]
        public ?GraphCompartmentUseType $use = null,
        /** @var CompartmentTypeType|null code Patient | Encounter | RelatedPerson | Practitioner | Device */
        #[NotBlank]
        public ?CompartmentTypeType $code = null,
        /** @var GraphCompartmentRuleType|null rule identical | matching | different | custom */
        #[NotBlank]
        public ?GraphCompartmentRuleType $rule = null,
        /** @var StringPrimitive|string|null expression Custom rule, as a FHIRPath expression */
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null description Documentation for FHIRPath expression */
        public StringPrimitive|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
