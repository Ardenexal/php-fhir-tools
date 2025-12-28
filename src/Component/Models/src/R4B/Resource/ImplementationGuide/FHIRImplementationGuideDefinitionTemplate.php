<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A template for building resources.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.template', fhirVersion: 'R4B')]
class FHIRImplementationGuideDefinitionTemplate extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Type of template specified */
        #[NotBlank]
        public ?\FHIRCode $code = null,
        /** @var FHIRString|string|null source The source location for the template */
        #[NotBlank]
        public \FHIRString|string|null $source = null,
        /** @var FHIRString|string|null scope The scope in which the template applies */
        public \FHIRString|string|null $scope = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
