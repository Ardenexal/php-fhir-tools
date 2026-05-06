<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicinalProductDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Reference to another product, e.g. for linking authorised to investigational product, or a virtual product.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductDefinition',
    elementPath: 'MedicinalProductDefinition.crossReference',
    fhirVersion: 'R5',
)]
class MedicinalProductDefinitionCrossReference extends BackboneElement
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
        /** @var CodeableReference|null product Reference to another product, e.g. for linking authorised to investigational product */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableReference $product = null,
        /** @var CodeableConcept|null type The type of relationship, for instance branded to generic or virtual to actual product */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
