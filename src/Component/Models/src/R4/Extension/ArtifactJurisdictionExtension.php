<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-jurisdiction
 *
 * @description A legal or geographic region in which the authority that maintains the resource is operating.     In general, the jurisdiction is also found in the [[[http://hl7.org/fhir/StructureDefinition/artifact-useContext]]] for the resource. The useContext may reference additional jurisdictions because the defining jurisdiction does not necessarily limit the jurisdictions of use. This extension SHALL not be used on any resource that has a defined `jurisdiction` element.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-jurisdiction', fhirVersion: 'R4')]
class ArtifactJurisdictionExtension extends Extension
{
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
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-jurisdiction',
            value: $this->valueCodeableConcept,
        );
    }
}
