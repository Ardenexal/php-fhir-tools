<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Information about the search process that lead to the creation of this entry.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.search', fhirVersion: 'R5')]
class FHIRBundleEntrySearch extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRSearchEntryModeType|null mode match | include - why this is in the result set */
        public ?\FHIRSearchEntryModeType $mode = null,
        /** @var FHIRDecimal|null score Search ranking (between 0 and 1) */
        public ?\FHIRDecimal $score = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
