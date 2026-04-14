<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The maximum number of units of the medication that can be dispensed in a period.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory.maxDispense', fhirVersion: 'R5')]
class MedicationKnowledgeRegulatoryMaxDispense extends BackboneElement
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
        /** @var Quantity|null quantity The maximum number of units of the medication that can be dispensed */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Quantity $quantity = null,
        /** @var Duration|null period The period that applies to the maximum number of units */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
