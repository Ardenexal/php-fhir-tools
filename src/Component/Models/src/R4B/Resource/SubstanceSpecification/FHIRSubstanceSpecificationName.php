<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Names applicable to this substance.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.name', fhirVersion: 'R4B')]
class FHIRSubstanceSpecificationName extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name The actual name */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null type Name type */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null status The status of the name */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRBoolean|null preferred If this is the preferred name for this substance */
        public ?FHIRBoolean $preferred = null,
        /** @var array<FHIRCodeableConcept> language Language of the name */
        public array $language = [],
        /** @var array<FHIRCodeableConcept> domain The use context of this name for example if there is a different name a drug active ingredient as opposed to a food colour additive */
        public array $domain = [],
        /** @var array<FHIRCodeableConcept> jurisdiction The jurisdiction where this name applies */
        public array $jurisdiction = [],
        /** @var array<FHIRSubstanceSpecificationName> synonym A synonym of this name */
        public array $synonym = [],
        /** @var array<FHIRSubstanceSpecificationName> translation A translation for this name */
        public array $translation = [],
        /** @var array<FHIRSubstanceSpecificationNameOfficial> official Details of the official nature of this name */
        public array $official = [],
        /** @var array<FHIRReference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
