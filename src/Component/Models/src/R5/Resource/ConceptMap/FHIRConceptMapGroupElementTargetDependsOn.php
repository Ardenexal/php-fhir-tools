<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A set of additional dependencies for this mapping to hold. This mapping is only applicable if the specified data attribute can be resolved, and it has the specified value.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ConceptMap', elementPath: 'ConceptMap.group.element.target.dependsOn', fhirVersion: 'R5')]
class FHIRConceptMapGroupElementTargetDependsOn extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null attribute A reference to a mapping attribute defined in ConceptMap.additionalAttribute */
        #[NotBlank]
        public ?FHIRCode $attribute = null,
        /** @var FHIRCode|FHIRCoding|FHIRString|string|FHIRBoolean|FHIRQuantity|null valueX Value of the referenced data element */
        public FHIRCode|FHIRCoding|FHIRString|string|FHIRBoolean|FHIRQuantity|null $valueX = null,
        /** @var FHIRCanonical|null valueSet The mapping depends on a data element with a value from this value set */
        public ?FHIRCanonical $valueSet = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
