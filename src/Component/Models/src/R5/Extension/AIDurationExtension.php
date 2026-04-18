<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-duration
 *
 * @description The amount of time that the Adverse Reaction persisted.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-duration', fhirVersion: 'R5')]
class AIDurationExtension extends Extension
{
    public function __construct(
        /** @var Duration|null valueDuration Value of extension */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $valueDuration = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-duration',
            value: $this->valueDuration,
        );
    }
}
