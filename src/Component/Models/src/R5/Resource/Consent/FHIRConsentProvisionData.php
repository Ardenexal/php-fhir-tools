<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRConsentDataMeaningType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The resources controlled by this provision if specific resources are referenced.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision.data', fhirVersion: 'R5')]
class FHIRConsentProvisionData extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
