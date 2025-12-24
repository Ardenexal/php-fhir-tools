<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;

/**
 * @description The legal status of supply of the packaged item as classified by the regulator.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'PackagedProductDefinition',
    elementPath: 'PackagedProductDefinition.legalStatusOfSupply',
    fhirVersion: 'R4B',
)]
class FHIRPackagedProductDefinitionLegalStatusOfSupply extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code The actual status of supply. In what situation this package type may be supplied for use */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRCodeableConcept|null jurisdiction The place where the legal status of supply applies */
        public ?FHIRCodeableConcept $jurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
