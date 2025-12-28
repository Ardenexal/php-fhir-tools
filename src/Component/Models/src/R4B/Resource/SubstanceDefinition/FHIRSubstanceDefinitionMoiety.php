<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Moiety, for structural modifications.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.moiety', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionMoiety extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null role Role that the moiety is playing */
        public ?FHIRCodeableConcept $role = null,
        /** @var FHIRIdentifier|null identifier Identifier by which this moiety substance is known */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null name Textual name for this moiety substance */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null stereochemistry Stereochemistry type */
        public ?FHIRCodeableConcept $stereochemistry = null,
        /** @var FHIRCodeableConcept|null opticalActivity Optical activity type */
        public ?FHIRCodeableConcept $opticalActivity = null,
        /** @var FHIRString|string|null molecularFormula Molecular formula for this moiety (e.g. with the Hill system) */
        public FHIRString|string|null $molecularFormula = null,
        /** @var FHIRQuantity|FHIRString|string|null amountX Quantitative value for this moiety */
        public FHIRQuantity|FHIRString|string|null $amountX = null,
        /** @var FHIRCodeableConcept|null measurementType The measurement type of the quantitative value */
        public ?FHIRCodeableConcept $measurementType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
