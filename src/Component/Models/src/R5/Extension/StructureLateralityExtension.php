<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-structureLaterality
 *
 * @description Indicates the laterality of the body structure. Only for use when laterality is needed to augment the Observation.bodySite in cases where the laterality is not expressed in the Observation.code and/or the use of BodyStructure will duplicate information for the anatomical structure. This extension is used with Observation.bodySite through FHIR R5. In FHIR R6 and later, the bodySite element is deprecated and replaced with the bodyStructure element with reference to BodyStructure, so this extension is no longer required.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-structureLaterality', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation.bodySite')]
#[FHIRProfileBinding(path: 'value[x]', valueSetUrl: 'http://hl7.org/fhir/ValueSet/structure-laterality-vs', strength: 'extensible')]
class StructureLateralityExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-structureLaterality',
            value: $this->valueCodeableConcept,
        );
    }
}
