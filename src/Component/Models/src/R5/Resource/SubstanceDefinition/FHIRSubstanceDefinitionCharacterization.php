<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description General specifications for this substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.characterization', fhirVersion: 'R5')]
class FHIRSubstanceDefinitionCharacterization extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null technique The method used to find the characterization e.g. HPLC */
        public ?\FHIRCodeableConcept $technique = null,
        /** @var FHIRCodeableConcept|null form Describes the nature of the chemical entity and explains, for instance, whether this is a base or a salt form */
        public ?\FHIRCodeableConcept $form = null,
        /** @var FHIRMarkdown|null description The description or justification in support of the interpretation of the data file */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRAttachment> file The data produced by the analytical instrument or a pictorial representation of that data. Examples: a JCAMP, JDX, or ADX file, or a chromatogram or spectrum analysis */
        public array $file = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
