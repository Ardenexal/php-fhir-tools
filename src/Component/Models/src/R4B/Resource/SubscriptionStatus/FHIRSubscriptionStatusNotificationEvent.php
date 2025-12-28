<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Detailed information about events relevant to this subscription notification.
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionStatus', elementPath: 'SubscriptionStatus.notificationEvent', fhirVersion: 'R4B')]
class FHIRSubscriptionStatusNotificationEvent extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null eventNumber Event number */
        #[NotBlank]
        public FHIRString|string|null $eventNumber = null,
        /** @var FHIRInstant|null timestamp The instant this event occurred */
        public ?FHIRInstant $timestamp = null,
        /** @var FHIRReference|null focus The focus of this event */
        public ?FHIRReference $focus = null,
        /** @var array<FHIRReference> additionalContext Additional context for this event */
        public array $additionalContext = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
