<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Names applicable to this substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.name', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionName extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        public \FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null type Name type e.g. 'systematic',  'scientific, 'brand' */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null status The status of the name e.g. 'current', 'proposed' */
        public ?\FHIRCodeableConcept $status = null,
        /** @var FHIRBoolean|null preferred If this is the preferred name for this substance */
        public ?\FHIRBoolean $preferred = null,
        /** @var array<FHIRCodeableConcept> language Human language that the name is written in */
        public array $language = [],
        /** @var array<FHIRCodeableConcept> domain The use context of this name e.g. as an active ingredient or as a food colour additive */
        public array $domain = [],
        /** @var array<FHIRCodeableConcept> jurisdiction The jurisdiction where this name applies */
        public array $jurisdiction = [],
        /** @var array<FHIRSubstanceDefinitionName> synonym A synonym of this particular name, by which the substance is also known */
        public array $synonym = [],
        /** @var array<FHIRSubstanceDefinitionName> translation A translation for this name into another human language */
        public array $translation = [],
        /** @var array<FHIRSubstanceDefinitionNameOfficial> official Details of the official nature of this name */
        public array $official = [],
        /** @var array<FHIRReference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
