<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Who or what is controlled by this provision. Use group to identify a set of actors by some property they share (e.g. 'admitting officers').
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision.actor', fhirVersion: 'R5')]
class FHIRConsentProvisionActor extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null role How the actor is involved */
        public ?\FHIRCodeableConcept $role = null,
        /** @var FHIRReference|null reference Resource for the actor (or group, by role) */
        public ?\FHIRReference $reference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
