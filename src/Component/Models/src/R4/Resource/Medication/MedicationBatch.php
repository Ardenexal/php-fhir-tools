<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Medication;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Information that only applies to packages (not products).
 */
#[FHIRBackboneElement(parentResource: 'Medication', elementPath: 'Medication.batch', fhirVersion: 'R4')]
class MedicationBatch extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null lotNumber Identifier assigned to batch */
        public StringPrimitive|string|null $lotNumber = null,
        /** @var DateTimePrimitive|null expirationDate When batch will expire */
        public ?DateTimePrimitive $expirationDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
