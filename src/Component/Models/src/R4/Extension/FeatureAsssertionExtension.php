<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/feature-assertion
 *
 * @description This extension asserts that the data in a resource was authored (collected/handled/created/transformed) by an application that claims conformance to the definition of a feature. Note that 'authoring' is often a client function, but that is not always the case.
 *
 *   For further information about features, see the [Application Feature Framework Implementation Guide](https://build.fhir.org/ig/HL7/capstmt/specification.html).
 *
 *   As an example of the kind of use this extension might support, an application could choose to only use value set  expansions that are explicitly labeled as 'prepared under the conformance rules defined in the [CRMI implementation guide](https://build.fhir.org/ig/HL7/crmi-ig).  This extension is a statement about the provenance of a particular version of the resource that it is describing; as  such, it should be made in a Provenance resource referring to that particular version. Alternatively, the extension  can be placed in the resource about which the assertion is made (in Resource.meta); in this case, the assertion  SHOULD be removed when the resource data is altered (it may be replaced by a new assertion).
 *
 *   This assertion is often used to drive processing rules associated with the trustworthiness of the data in  the resource. Applications/specifications/workflows that make use of this assertion should carefully consider the integrity of the chain of handling from the source the processing before choosing to trust the assertion.
 *
 *   A more complex alternative to this profile is to use the [[[http://hl7.org/fhir/StructureDefinition/obligations-profile]]] extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/feature-assertion', fhirVersion: 'R4')]
class FeatureAsssertionExtension extends Extension
{
    public function __construct(
        /** @var Coding|null valueCoding A code that identifies a feature */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $valueCoding = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/feature-assertion',
            value: $this->valueCoding,
        );
    }
}
