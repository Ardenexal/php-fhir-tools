<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The resources controlled by this rule if specific resources are referenced.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision.data', fhirVersion: 'R4B')]
class FHIRConsentProvisionData extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRConsentDataMeaningType|null meaning instance | related | dependents | authoredby */
        #[NotBlank]
        public ?FHIRConsentDataMeaningType $meaning = null,
        /** @var FHIRReference|null reference The actual data reference */
        #[NotBlank]
        public ?FHIRReference $reference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
