<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPackaged;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Batch numbering.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductPackaged', elementPath: 'MedicinalProductPackaged.batchIdentifier', fhirVersion: 'R4')]
class MedicinalProductPackagedBatchIdentifier extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Identifier|null outerPackaging A number appearing on the outer packaging of a specific batch */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Identifier $outerPackaging = null,
        /** @var Identifier|null immediatePackaging A number appearing on the immediate packaging (and not the outer packaging) */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $immediatePackaging = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
