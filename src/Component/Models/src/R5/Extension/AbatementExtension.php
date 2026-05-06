<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/allergyintolerance-abatement
 *
 * @description The date or estimated date that the allergy or intolerance resolved. This is called abatement because of the many overloaded connotations associated with resolution.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-abatement', fhirVersion: 'R5')]
class AbatementExtension extends Extension
{
    public function __construct(
        /** @var DateTimePrimitive|Age|Period|Range|StringPrimitive|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        DateTimePrimitive|Age|Period|Range|StringPrimitive|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/allergyintolerance-abatement',
            value: $value,
        );
    }
}
