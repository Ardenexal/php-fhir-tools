<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Practitioner;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A language which may be used to communicate with the practitioner, often for correspondence/administrative purposes.
 *
 * The `PractitionerRole.communication` property should be used for publishing the languages that a practitioner is able to communicate with patients (on a per Organization/Role basis).
 */
#[FHIRBackboneElement(parentResource: 'Practitioner', elementPath: 'Practitioner.communication', fhirVersion: 'R5')]
class PractitionerCommunication extends BackboneElement
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
        /** @var CodeableConcept|null language The language code used to communicate with the practitioner */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $language = null,
        /** @var bool|null preferred Language preference indicator */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $preferred = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
