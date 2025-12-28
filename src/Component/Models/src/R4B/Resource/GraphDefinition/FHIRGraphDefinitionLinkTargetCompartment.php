<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Compartment Consistency Rules.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link.target.compartment', fhirVersion: 'R4B')]
class FHIRGraphDefinitionLinkTargetCompartment extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRGraphCompartmentUseType|null use condition | requirement */
        #[NotBlank]
        public ?FHIRGraphCompartmentUseType $use = null,
        /** @var FHIRCompartmentTypeType|null code Patient | Encounter | RelatedPerson | Practitioner | Device */
        #[NotBlank]
        public ?FHIRCompartmentTypeType $code = null,
        /** @var FHIRGraphCompartmentRuleType|null rule identical | matching | different | custom */
        #[NotBlank]
        public ?FHIRGraphCompartmentRuleType $rule = null,
        /** @var FHIRString|string|null expression Custom rule, as a FHIRPath expression */
        public FHIRString|string|null $expression = null,
        /** @var FHIRString|string|null description Documentation for FHIRPath expression */
        public FHIRString|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
