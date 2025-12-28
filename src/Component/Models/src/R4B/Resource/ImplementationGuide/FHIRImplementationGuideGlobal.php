<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A set of profiles that all resources covered by this implementation guide must conform to.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.global', fhirVersion: 'R4B')]
class FHIRImplementationGuideGlobal extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRResourceTypeType|null type Type this profile applies to */
        #[NotBlank]
        public ?\FHIRResourceTypeType $type = null,
        /** @var FHIRCanonical|null profile Profile that all resources must conform to */
        #[NotBlank]
        public ?\FHIRCanonical $profile = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
