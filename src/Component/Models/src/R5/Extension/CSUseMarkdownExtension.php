<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-use-markdown
 *
 * @description If true, the definitions of the concepts should be treated and rendered as markdown for improved presentation.  This extension is deprecated as the Terminology Infrastructure work group felt there wasn't a use case for the extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-use-markdown', fhirVersion: 'R5')]
class CSUseMarkdownExtension extends Extension
{
    public function __construct(
        /** @var bool|null valueBoolean Value of extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueBoolean = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-use-markdown',
            value: $this->valueBoolean,
        );
    }
}
