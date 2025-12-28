<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Description of the semantic space the Value Set Expansion is intended to cover and should further clarify the text in ValueSet.description.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.scope', fhirVersion: 'R5')]
class FHIRValueSetScope extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null inclusionCriteria Criteria describing which concepts or codes should be included and why */
        public \FHIRString|string|null $inclusionCriteria = null,
        /** @var FHIRString|string|null exclusionCriteria Criteria describing which concepts or codes should be excluded and why */
        public \FHIRString|string|null $exclusionCriteria = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
