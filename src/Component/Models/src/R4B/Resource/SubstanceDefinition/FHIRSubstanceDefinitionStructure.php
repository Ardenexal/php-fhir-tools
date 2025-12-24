<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Structural information.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.structure', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionStructure extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null stereochemistry Stereochemistry type */
        public ?FHIRCodeableConcept $stereochemistry = null,
        /** @var FHIRCodeableConcept|null opticalActivity Optical activity type */
        public ?FHIRCodeableConcept $opticalActivity = null,
        /** @var FHIRString|string|null molecularFormula Molecular formula (e.g. using the Hill system) */
        public FHIRString|string|null $molecularFormula = null,
        /** @var FHIRString|string|null molecularFormulaByMoiety Specified per moiety according to the Hill system */
        public FHIRString|string|null $molecularFormulaByMoiety = null,
        /** @var FHIRSubstanceDefinitionMolecularWeight|null molecularWeight The molecular weight or weight range */
        public ?FHIRSubstanceDefinitionMolecularWeight $molecularWeight = null,
        /** @var array<FHIRCodeableConcept> technique The method used to find the structure e.g. X-ray, NMR */
        public array $technique = [],
        /** @var array<FHIRReference> sourceDocument Source of information for the structure */
        public array $sourceDocument = [],
        /** @var array<FHIRSubstanceDefinitionStructureRepresentation> representation A depiction of the structure or characterization of the substance */
        public array $representation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
