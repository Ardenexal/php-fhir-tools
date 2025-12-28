<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A property defines an additional slot through which additional information can be provided about a concept.
 */
#[FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.property', fhirVersion: 'R4')]
class FHIRCodeSystemProperty extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Identifies the property on the concepts, and when referred to in operations */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRUri|null uri Formal identifier for the property */
        public ?FHIRUri $uri = null,
        /** @var FHIRString|string|null description Why the property is defined, and/or what it conveys */
        public FHIRString|string|null $description = null,
        /** @var FHIRPropertyTypeType|null type code | Coding | string | integer | boolean | dateTime | decimal */
        #[NotBlank]
        public ?FHIRPropertyTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
