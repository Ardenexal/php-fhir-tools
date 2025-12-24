<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement;

/**
 * @description Code filters specify additional constraints on the data, specifying the value set of interest for a particular element of the data. Each code filter defines an additional constraint on the data, i.e. code filters are AND'ed, not OR'ed.
 */
#[FHIRComplexType(typeName: 'DataRequirement.codeFilter', fhirVersion: 'R5')]
class FHIRDataRequirementCodeFilter extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null path A code-valued attribute to filter on */
        public FHIRString|string|null $path = null,
        /** @var FHIRString|string|null searchParam A coded (token) parameter to search on */
        public FHIRString|string|null $searchParam = null,
        /** @var FHIRCanonical|null valueSet ValueSet for the filter */
        public ?FHIRCanonical $valueSet = null,
        /** @var array<FHIRCoding> code What code is expected */
        public array $code = [],
    ) {
        parent::__construct($id, $extension);
    }
}
