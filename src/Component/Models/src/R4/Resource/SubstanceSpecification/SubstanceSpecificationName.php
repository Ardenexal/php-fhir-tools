<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Names applicable to this substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.name', fhirVersion: 'R4')]
class SubstanceSpecificationName extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name The actual name */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var CodeableConcept|null type Name type */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null status The status of the name */
        public ?CodeableConcept $status = null,
        /** @var bool|null preferred If this is the preferred name for this substance */
        public ?bool $preferred = null,
        /** @var array<CodeableConcept> language Language of the name */
        public array $language = [],
        /** @var array<CodeableConcept> domain The use context of this name for example if there is a different name a drug active ingredient as opposed to a food colour additive */
        public array $domain = [],
        /** @var array<CodeableConcept> jurisdiction The jurisdiction where this name applies */
        public array $jurisdiction = [],
        /** @var array<SubstanceSpecificationName> synonym A synonym of this name */
        public array $synonym = [],
        /** @var array<SubstanceSpecificationName> translation A translation for this name */
        public array $translation = [],
        /** @var array<SubstanceSpecificationNameOfficial> official Details of the official nature of this name */
        public array $official = [],
        /** @var array<Reference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
