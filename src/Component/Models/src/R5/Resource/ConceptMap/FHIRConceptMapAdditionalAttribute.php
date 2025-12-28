<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An additionalAttribute defines an additional data element found in the source or target data model where the data will come from or be mapped to. Some mappings are based on data in addition to the source data element, where codes in multiple fields are combined to a single field (or vice versa).
 */
#[FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.additionalAttribute', fhirVersion: 'R5')]
class FHIRConceptMapAdditionalAttribute extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Identifies this additional attribute through this resource */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRUri|null uri Formal identifier for the data element referred to in this attribte */
        public ?FHIRUri $uri = null,
        /** @var FHIRString|string|null description Why the additional attribute is defined, and/or what the data element it refers to is */
        public FHIRString|string|null $description = null,
        /** @var FHIRConceptMapAttributeTypeType|null type code | Coding | string | boolean | Quantity */
        #[NotBlank]
        public ?FHIRConceptMapAttributeTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
