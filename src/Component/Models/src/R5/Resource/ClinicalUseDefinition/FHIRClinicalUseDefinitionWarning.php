<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description A critical piece of information about environmental, health or physical risks or hazards that serve as caution to the user. For example 'Do not operate heavy machinery', 'May cause drowsiness', or 'Get medical advice/attention if you feel unwell'.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.warning', fhirVersion: 'R5')]
class FHIRClinicalUseDefinitionWarning extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRMarkdown|null description A textual definition of this warning, with formatting */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRCodeableConcept|null code A coded or unformatted textual definition of this warning */
        public ?FHIRCodeableConcept $code = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
