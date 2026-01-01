<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExposureStateType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A description of the results for each exposure considered in the effect estimate.
 */
#[FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.resultsByExposure', fhirVersion: 'R5')]
class FHIREffectEvidenceSynthesisResultsByExposure extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Description of results by exposure */
        public FHIRString|string|null $description = null,
        /** @var FHIRExposureStateType|null exposureState exposure | exposure-alternative */
        public ?FHIRExposureStateType $exposureState = null,
        /** @var FHIRCodeableConcept|null variantState Variant exposure states */
        public ?FHIRCodeableConcept $variantState = null,
        /** @var FHIRReference|null riskEvidenceSynthesis Risk evidence synthesis */
        #[NotBlank]
        public ?FHIRReference $riskEvidenceSynthesis = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
