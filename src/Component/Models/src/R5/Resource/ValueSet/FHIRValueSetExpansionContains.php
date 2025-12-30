<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description The codes that are contained in the value set expansion.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion.contains', fhirVersion: 'R5')]
class FHIRValueSetExpansionContains extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null system System value for the code */
        public ?FHIRUri $system = null,
        /** @var FHIRBoolean|null abstract If user cannot select this entry */
        public ?FHIRBoolean $abstract = null,
        /** @var FHIRBoolean|null inactive If concept is inactive in the code system */
        public ?FHIRBoolean $inactive = null,
        /** @var FHIRString|string|null version Version in which this code/display is defined */
        public FHIRString|string|null $version = null,
        /** @var FHIRCode|null code Code - if blank, this is not a selectable code */
        public ?FHIRCode $code = null,
        /** @var FHIRString|string|null display User display for the concept */
        public FHIRString|string|null $display = null,
        /** @var array<FHIRValueSetComposeIncludeConceptDesignation> designation Additional representations for this item */
        public array $designation = [],
        /** @var array<FHIRValueSetExpansionContainsProperty> property Property value for the concept */
        public array $property = [],
        /** @var array<FHIRValueSetExpansionContains> contains Codes contained under this entry */
        public array $contains = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
