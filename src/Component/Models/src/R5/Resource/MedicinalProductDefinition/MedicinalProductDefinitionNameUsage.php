<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicinalProductDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Country and jurisdiction where the name applies, and associated language.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductDefinition', elementPath: 'MedicinalProductDefinition.name.usage', fhirVersion: 'R5')]
class MedicinalProductDefinitionNameUsage extends BackboneElement
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
        /** @var CodeableConcept|null country Country code for where this name applies */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $country = null,
        /** @var CodeableConcept|null jurisdiction Jurisdiction code for where this name applies */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $jurisdiction = null,
        /** @var CodeableConcept|null language Language code for this name */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $language = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
