<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/servicerequest-questionnaireRequest
 *
 * @description Reference to a specific Questionnaire Resource as an ordered item.  Allows for ordering a specific questionnaire to be completed.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-questionnaireRequest', fhirVersion: 'R4B')]
class QuestionnaireRequestExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/servicerequest-questionnaireRequest',
            value: $this->valueReference,
        );
    }
}
