<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A suite of underwriter specific classifiers.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Coverage', elementPath: 'Coverage.class', fhirVersion: 'R4B')]
class FHIRCoverageClass extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of class such as 'group' or 'plan' */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null value Value associated with the type */
        #[NotBlank]
        public FHIRString|string|null $value = null,
        /** @var FHIRString|string|null name Human readable description of the type and value */
        public FHIRString|string|null $name = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
