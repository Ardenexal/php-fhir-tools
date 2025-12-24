<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A description of the results for each exposure considered in the effect estimate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.resultsByExposure', fhirVersion: 'R4')]
class FHIREffectEvidenceSynthesisResultsByExposure extends FHIRBackboneElement
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
