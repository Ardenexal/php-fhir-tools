<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Detailed information about events relevant to this subscription notification.
 */
#[FHIRBackboneElement(parentResource: 'SubscriptionStatus', elementPath: 'SubscriptionStatus.notificationEvent', fhirVersion: 'R5')]
class FHIRSubscriptionStatusNotificationEvent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger64|null eventNumber Sequencing index of this event */
        #[NotBlank]
        public ?\FHIRInteger64 $eventNumber = null,
        /** @var FHIRInstant|null timestamp The instant this event occurred */
        public ?\FHIRInstant $timestamp = null,
        /** @var FHIRReference|null focus Reference to the primary resource or information of this event */
        public ?\FHIRReference $focus = null,
        /** @var array<FHIRReference> additionalContext References related to the focus resource and/or context of this event */
        public array $additionalContext = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
