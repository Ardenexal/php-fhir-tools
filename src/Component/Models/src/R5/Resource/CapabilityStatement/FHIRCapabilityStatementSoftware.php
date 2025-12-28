<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Software that is covered by this capability statement.  It is used when the capability statement describes the capabilities of a particular software version, independent of an installation.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.software', fhirVersion: 'R5')]
class FHIRCapabilityStatementSoftware extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name A name the software is known by */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null version Version covered by this statement */
        public \FHIRString|string|null $version = null,
        /** @var FHIRDateTime|null releaseDate Date this version was released */
        public ?\FHIRDateTime $releaseDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
