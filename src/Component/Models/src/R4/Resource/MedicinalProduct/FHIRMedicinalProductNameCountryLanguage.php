<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Country where the name applies.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.name.countryLanguage', fhirVersion: 'R4')]
class FHIRMedicinalProductNameCountryLanguage extends FHIRBackboneElement
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
        public ?FHIRCodeableConcept $country = null,
        /** @var FHIRCodeableConcept|null jurisdiction Jurisdiction code for where this name applies */
        public ?FHIRCodeableConcept $jurisdiction = null,
        /** @var FHIRCodeableConcept|null language Language code for this name */
        #[NotBlank]
        public ?FHIRCodeableConcept $language = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
