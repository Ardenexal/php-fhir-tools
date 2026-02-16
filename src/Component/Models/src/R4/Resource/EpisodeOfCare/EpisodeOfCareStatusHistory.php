<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\EpisodeOfCare;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EpisodeOfCareStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The history of statuses that the EpisodeOfCare has been through (without requiring processing the history of the resource).
 */
#[FHIRBackboneElement(parentResource: 'EpisodeOfCare', elementPath: 'EpisodeOfCare.statusHistory', fhirVersion: 'R4')]
class EpisodeOfCareStatusHistory extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var EpisodeOfCareStatusType|null status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
        #[NotBlank]
        public ?EpisodeOfCareStatusType $status = null,
        /** @var Period|null period Duration the EpisodeOfCare was in the specified status */
        #[NotBlank]
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
