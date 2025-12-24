<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description A statistical attribute of the statistic such as a measure of heterogeneity.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.statistic.attributeEstimate', fhirVersion: 'R4B')]
class FHIREvidenceStatisticAttributeEstimate extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Textual description of the attribute estimate */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRAnnotation> note Footnote or explanatory note about the estimate */
        public array $note = [],
        /** @var FHIRCodeableConcept|null type The type of attribute estimate, eg confidence interval or p value */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRQuantity|null quantity The singular quantity of the attribute estimate, for attribute estimates represented as single values; also used to report unit of measure */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRDecimal|null level Level of confidence interval, eg 0.95 for 95% confidence interval */
        public ?FHIRDecimal $level = null,
        /** @var FHIRRange|null range Lower and upper bound values of the attribute estimate */
        public ?FHIRRange $range = null,
        /** @var array<FHIREvidenceStatisticAttributeEstimate> attributeEstimate A nested attribute estimate; which is the attribute estimate of an attribute estimate */
        public array $attributeEstimate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
