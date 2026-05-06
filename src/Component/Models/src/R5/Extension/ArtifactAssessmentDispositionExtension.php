<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifactassessment-disposition
 *
 * @description Possible values for the disposition of a comment or change request, typically used for comments and change requests, to indicate the disposition of the responsible party towards the changes suggested by the comment or change request.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifactassessment-disposition', fhirVersion: 'R5')]
class ArtifactAssessmentDispositionExtension extends Extension
{
    public function __construct(
        /** @var CodePrimitive|null valueCode unresolved | not-persuasive | persuasive | persuasive-with-modification | not-persuasive-with-modification */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifactassessment-disposition',
            value: $this->valueCode,
        );
    }
}
