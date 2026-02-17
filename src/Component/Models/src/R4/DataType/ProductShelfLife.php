<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ProductShelfLife
 *
 * @description The shelf-life and storage information for a medicinal product item or container can be described using this class.
 */
#[FHIRBackboneElement(parentResource: 'ProductShelfLife', elementPath: 'ProductShelfLife', fhirVersion: 'R4')]
class ProductShelfLife extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Unique identifier for the packaged Medicinal Product */
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null type This describes the shelf life, taking into account various scenarios such as shelf life of the packaged Medicinal Product itself, shelf life after transformation where necessary and shelf life after the first opening of a bottle, etc. The shelf life type shall be specified using an appropriate controlled vocabulary The controlled term and the controlled term identifier shall be specified */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Quantity|null period The shelf life time period can be specified using a numerical value for the period of time and its unit of time measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        #[NotBlank]
        public ?Quantity $period = null,
        /** @var array<CodeableConcept> specialPrecautionsForStorage Special precautions for storage, if any, can be specified using an appropriate controlled vocabulary The controlled term and the controlled term identifier shall be specified */
        public array $specialPrecautionsForStorage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
