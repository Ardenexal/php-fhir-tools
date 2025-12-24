<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A property defines a slot through which additional information can be provided about a map from source -> target.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.property', fhirVersion: 'R5')]
class FHIRConceptMapProperty extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Identifies the property on the mappings, and when referred to in the $translate operation */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRUri|null uri Formal identifier for the property */
        public ?FHIRUri $uri = null,
        /** @var FHIRString|string|null description Why the property is defined, and/or what it conveys */
        public FHIRString|string|null $description = null,
        /** @var FHIRConceptMapPropertyTypeType|null type Coding | string | integer | boolean | dateTime | decimal | code */
        #[NotBlank]
        public ?FHIRConceptMapPropertyTypeType $type = null,
        /** @var FHIRCanonical|null system The CodeSystem from which code values come */
        public ?FHIRCanonical $system = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
