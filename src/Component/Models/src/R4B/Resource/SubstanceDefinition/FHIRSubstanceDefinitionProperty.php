<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description General specifications for this substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.property', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionProperty extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type A code expressing the type of property */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRDate|FHIRBoolean|FHIRAttachment|null valueX A value for the property */
        public \FHIRCodeableConcept|\FHIRQuantity|\FHIRDate|\FHIRBoolean|\FHIRAttachment|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
