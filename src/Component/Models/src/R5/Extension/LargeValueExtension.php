<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/largeValue
 *
 * @description The value of an integer that exceeds the bounds of the base int type.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/largeValue', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'integer')]
#[FHIRProfileInvariant(
    path: '',
    key: 'lv-1',
    severity: 'error',
    expression: 'value.toString().matches(\'^-?[0-9]+$\')',
    human: 'The value must be a whole number (no decimal point or exponent)',
)]
class LargeValueExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var string|Integer64Primitive|null value Value of extension */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'valueDecimal'],
                [
                    'fhirType'     => 'integer64',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\Integer64Primitive',
                    'jsonKey'      => 'valueInteger64',
                ],
            ],
        )]
        string|Integer64Primitive|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/largeValue',
            value: $value,
        );
    }
}
