<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Information that only applies to packages (not products).
 */
#[FHIRBackboneElement(parentResource: 'Medication', elementPath: 'Medication.batch', fhirVersion: 'R5')]
class FHIRMedicationBatch extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null lotNumber Identifier assigned to batch */
        public \FHIRString|string|null $lotNumber = null,
        /** @var FHIRDateTime|null expirationDate When batch will expire */
        public ?\FHIRDateTime $expirationDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
