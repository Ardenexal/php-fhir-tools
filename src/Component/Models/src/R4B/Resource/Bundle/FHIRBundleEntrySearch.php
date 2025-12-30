<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSearchEntryModeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;

/**
 * @description Information about the search process that lead to the creation of this entry.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.search', fhirVersion: 'R4B')]
class FHIRBundleEntrySearch extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRSearchEntryModeType|null mode match | include | outcome - why this is in the result set */
        public ?FHIRSearchEntryModeType $mode = null,
        /** @var FHIRDecimal|null score Search ranking (between 0 and 1) */
        public ?FHIRDecimal $score = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
