<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The supplemental data criteria for the measure report, specified as either the name of a valid CQL expression within a referenced library, or a valid FHIR Resource Path.
 */
#[FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.supplementalData', fhirVersion: 'R5')]
class FHIRMeasureSupplementalData extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Unique id for supplementalData in measure */
        public FHIRString|string|null $linkId = null,
        /** @var FHIRCodeableConcept|null code Meaning of the supplemental data */
        public ?FHIRCodeableConcept $code = null,
        /** @var array<FHIRCodeableConcept> usage supplemental-data | risk-adjustment-factor */
        public array $usage = [],
        /** @var FHIRMarkdown|null description The human readable description of this supplemental data */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRExpression|null criteria Expression describing additional data to be reported */
        #[NotBlank]
        public ?FHIRExpression $criteria = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
