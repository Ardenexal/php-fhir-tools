<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-modelInfo-isIncluded
 *
 * @description Specifies whether the profile should be included in the model info constructed for an artifact collection such as an implementation guide. If this extension is not present, included is true by default for resources and profiles, but not data types (unless they are indirectly referenced by included resources or profiles). Note that even if isIncluded is false for a resource or profile, it will still be included in model info if it is a required dependency of some other included resource, profile, or data type.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-modelInfo-isIncluded', fhirVersion: 'R4')]
class ModelInfoIsIncludedExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-modelInfo-isIncluded',
            value: $this->valueBoolean,
        );
    }
}
