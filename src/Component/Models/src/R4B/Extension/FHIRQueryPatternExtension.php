<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-fhirQueryPattern
 *
 * @description A FHIR Query URL pattern that corresponds to the data specified by the data requirement. If multiple FHIR Query URLs are present, they each contribute to the data specified by the data requirement (i.e. the union of the results of the FHIR Queries represents the complete data for the data requirement). This is not a resolveable URL, in that it will contain 1) No base canonical (i.e. it's a relative query), and 2) Parameters using tokens that are delimited using double-braces and the context parameters are dependent solely on the subjectType, according to the following: Patient: context.patientId, Practitioner: context.practitionerId, Organization: context.organizationId, Location: context.locationId, Device: context.deviceId. For example, for a Library with a subjectType of Patient, the context parameter `{{context.patientId}}` will be used as a token to be replaced with the `id` of the Patient in context. This extension is used primarily to address the use case for satisfying a data requirement for a single subject. However, the query pattern could also be used to satisfy population level requests by removing the subject-level filter from the query.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-fhirQueryPattern', fhirVersion: 'R4B')]
class FHIRQueryPatternExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-fhirQueryPattern',
            value: $this->valueString,
        );
    }
}
