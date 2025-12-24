<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBindingStrengthType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Binds to a value set if this element is coded (code, Coding, CodeableConcept, Quantity), or the data types (string, uri).
 */
#[FHIRComplexType(typeName: 'ElementDefinition.binding', fhirVersion: 'R4')]
class FHIRElementDefinitionBinding extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRBindingStrengthType|null strength required | extensible | preferred | example */
        #[NotBlank]
        public ?FHIRBindingStrengthType $strength = null,
        /** @var FHIRString|string|null description Human explanation of the value set */
        public FHIRString|string|null $description = null,
        /** @var FHIRCanonical|null valueSet Source of value set */
        public ?FHIRCanonical $valueSet = null,
    ) {
        parent::__construct($id, $extension);
    }
}
