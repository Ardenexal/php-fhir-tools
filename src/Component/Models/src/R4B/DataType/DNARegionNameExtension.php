<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsDNARegionName
 *
 * @description The material on this page will be removed in a future release. This content is deprecated and SHOULD NOT be used. Implementers are instead directed to the ([Genomics Reporting Implementation Guide](http://hl7.org/fhir/uv/genomics-reporting/index.html)) for guidance. A region (or regions) that includes all of the sequence elements necessary to encode a functional transcript. A gene may include regulatory regions, transcribed regions and/or other functional sequence regions ([SO:0000704](http://www.sequenceontology.org/browser/current_svn/term/SO:0000704)). This element is the official gene symbol approved by the HGNC, which is a short abbreviated form of the gene name ([HGNC](http://www.genenames.org)). LOINC Code: ([48018-6](http://loinc.org/48018-6)).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsDNARegionName', fhirVersion: 'R4B')]
class DNARegionNameExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsDNARegionName',
            value: $this->valueString,
        );
    }
}
