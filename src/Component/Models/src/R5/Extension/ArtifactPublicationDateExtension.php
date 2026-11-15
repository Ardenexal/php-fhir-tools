<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-publicationDate
 *
 * @description Specifies the publication date of the Resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-publicationDate', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Composition')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
#[FHIRExtensionContext(type: 'element', expression: 'Medication')]
#[FHIRExtensionContext(type: 'element', expression: 'Substance')]
#[FHIRExtensionContext(type: 'element', expression: 'ResearchStudy')]
#[FHIRExtensionContext(type: 'element', expression: 'ArtifactAssessment')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ChargeItemDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Citation')]
#[FHIRExtensionContext(type: 'element', expression: 'CodeSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'ConceptMap')]
#[FHIRExtensionContext(type: 'element', expression: 'ConditionDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'EventDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Evidence')]
#[FHIRExtensionContext(type: 'element', expression: 'EvidenceVariable')]
#[FHIRExtensionContext(type: 'element', expression: 'Library')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure')]
#[FHIRExtensionContext(type: 'element', expression: 'NamingSystem')]
#[FHIRExtensionContext(type: 'element', expression: 'ObservationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire')]
#[FHIRExtensionContext(type: 'element', expression: 'SpecimenDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet')]
class ArtifactPublicationDateExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var DatePrimitive|null valueDate Value of extension */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $valueDate = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-publicationDate',
            value: $this->valueDate,
        );
    }
}
