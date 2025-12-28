<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUrl;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a specific implementation instance that is described by the capability statement - i.e. a particular installation, rather than the capabilities of a software program.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.implementation', fhirVersion: 'R4')]
class FHIRCapabilityStatementImplementation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Describes this specific instance */
        #[NotBlank]
        public FHIRString|string|null $description = null,
        /** @var FHIRUrl|null url Base URL for the installation */
        public ?FHIRUrl $url = null,
        /** @var FHIRReference|null custodian Organization that manages the data */
        public ?FHIRReference $custodian = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
