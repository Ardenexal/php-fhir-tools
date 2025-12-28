<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Country and jurisdiction where the name applies, and associated language.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductDefinition',
    elementPath: 'MedicinalProductDefinition.name.countryLanguage',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductDefinitionNameCountryLanguage extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null country Country code for where this name applies */
        #[NotBlank]
        public ?\FHIRCodeableConcept $country = null,
        /** @var FHIRCodeableConcept|null jurisdiction Jurisdiction code for where this name applies */
        public ?\FHIRCodeableConcept $jurisdiction = null,
        /** @var FHIRCodeableConcept|null language Language code for this name */
        #[NotBlank]
        public ?\FHIRCodeableConcept $language = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
