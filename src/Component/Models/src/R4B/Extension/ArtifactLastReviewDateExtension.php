<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DatePrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-lastReviewDate
 *
 * @description When the artifact was last reviewed by the publisher.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-lastReviewDate', fhirVersion: 'R4B')]
class ArtifactLastReviewDateExtension extends Extension
{
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
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-lastReviewDate',
            value: $this->valueDate,
        );
    }
}
