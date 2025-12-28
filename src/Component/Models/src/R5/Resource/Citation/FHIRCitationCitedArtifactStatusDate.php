<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An effective date or period, historical or future, actual or expected, for a status of the cited artifact.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.statusDate', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactStatusDate extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null activity Classification of the status */
        #[NotBlank]
        public ?\FHIRCodeableConcept $activity = null,
        /** @var FHIRBoolean|null actual Either occurred or expected */
        public ?\FHIRBoolean $actual = null,
        /** @var FHIRPeriod|null period When the status started and/or ended */
        #[NotBlank]
        public ?\FHIRPeriod $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
