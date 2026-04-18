<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;

/**
 * @author HL7 International / Clinical Quality Information
 *
 * @see http://hl7.org/fhir/StructureDefinition/measurereport-populationDescription
 *
 * @description The human readable description of population criteria.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/measurereport-populationDescription', fhirVersion: 'R4')]
class MeasureReportPopulationDescriptionExtension extends Extension
{
    public function __construct(
        /** @var MarkdownPrimitive|null valueMarkdown Value of extension */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $valueMarkdown = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/measurereport-populationDescription',
            value: $this->valueMarkdown,
        );
    }
}
