<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Patient diagnosis for which care is sought.
 */
#[FHIRBackboneElement(
    parentResource: 'CoverageEligibilityRequest',
    elementPath: 'CoverageEligibilityRequest.item.diagnosis',
    fhirVersion: 'R4',
)]
class CoverageEligibilityRequestItemDiagnosis extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|Reference|null diagnosisX Nature of illness or problem */
        public CodeableConcept|Reference|null $diagnosisX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
