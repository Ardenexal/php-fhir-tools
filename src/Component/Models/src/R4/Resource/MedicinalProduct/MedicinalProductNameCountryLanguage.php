<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Country where the name applies.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.name.countryLanguage', fhirVersion: 'R4')]
class MedicinalProductNameCountryLanguage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null country Country code for where this name applies */
        #[NotBlank]
        public ?CodeableConcept $country = null,
        /** @var CodeableConcept|null jurisdiction Jurisdiction code for where this name applies */
        public ?CodeableConcept $jurisdiction = null,
        /** @var CodeableConcept|null language Language code for this name */
        #[NotBlank]
        public ?CodeableConcept $language = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
