<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SearchEntryModeType;

/**
 * @description Information about the search process that lead to the creation of this entry.
 */
#[FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.search', fhirVersion: 'R4')]
class BundleEntrySearch extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var SearchEntryModeType|null mode match | include | outcome - why this is in the result set */
        public ?SearchEntryModeType $mode = null,
        /** @var float|null score Search ranking (between 0 and 1) */
        public ?float $score = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
