<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/specimen-additive
 *
 * @description References a resource or provides a code or text that specifies a substance that is added to a laboratory specimen (part of the container device or added to the specimen indenpendently).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/specimen-additive', fhirVersion: 'R5')]
class SpecimenAdditiveExtension extends Extension
{
    public function __construct(
        /** @var CodeableReference|null valueCodeableReference Value of extension */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $valueCodeableReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/specimen-additive',
            value: $this->valueCodeableReference,
        );
    }
}
