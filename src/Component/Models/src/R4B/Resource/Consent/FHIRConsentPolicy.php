<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @description The references to the policies that are included in this consent scope. Policies may be organizational, but are often defined jurisdictionally, or in law.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.policy', fhirVersion: 'R4B')]
class FHIRConsentPolicy extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null authority Enforcement source for policy */
        public ?FHIRUri $authority = null,
        /** @var FHIRUri|null uri Specific policy covered by this consent */
        public ?FHIRUri $uri = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
