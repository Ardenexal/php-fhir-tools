<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;

/**
 * @description Expressions that describe applicability criteria for the billing code.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.applicability', fhirVersion: 'R5')]
class FHIRChargeItemDefinitionApplicability extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRExpression|null condition Boolean-valued expression */
        public ?FHIRExpression $condition = null,
        /** @var FHIRPeriod|null effectivePeriod When the charge item definition is expected to be used */
        public ?FHIRPeriod $effectivePeriod = null,
        /** @var FHIRRelatedArtifact|null relatedArtifact Reference to / quotation of the external source of the group of properties */
        public ?FHIRRelatedArtifact $relatedArtifact = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
