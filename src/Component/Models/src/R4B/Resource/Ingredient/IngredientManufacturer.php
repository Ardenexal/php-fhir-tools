<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Ingredient;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\IngredientManufacturerRoleType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The organization(s) that manufacture this ingredient. Can be used to indicate:         1) Organizations we are aware of that manufacture this ingredient         2) Specific Manufacturer(s) currently being used         3) Set of organisations allowed to manufacture this ingredient for this product         Users must be clear on the application of context relevant to their use case.
 */
#[FHIRBackboneElement(parentResource: 'Ingredient', elementPath: 'Ingredient.manufacturer', fhirVersion: 'R4B')]
class IngredientManufacturer extends BackboneElement
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
        /** @var IngredientManufacturerRoleType|null role allowed | possible | actual */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?IngredientManufacturerRoleType $role = null,
        /** @var Reference|null manufacturer An organization that manufactures this ingredient */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $manufacturer = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
