<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description Value filters specify additional constraints on the data for elements other than code-valued or date-valued. Each value filter specifies an additional constraint on the data (i.e. valueFilters are AND'ed, not OR'ed).
 */
#[FHIRComplexType(typeName: 'DataRequirement.valueFilter', fhirVersion: 'R5')]
class DataRequirementValueFilter extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var StringPrimitive|string|null path An attribute to filter on */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $path = null,
        /** @var StringPrimitive|string|null searchParam A parameter to search on */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $searchParam = null,
        /** @var ValueFilterComparatorType|null comparator eq | gt | lt | ge | le | sa | eb */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?ValueFilterComparatorType $comparator = null,
        /** @var DateTimePrimitive|Period|Duration|null value The value of the filter, as a Period, DateTime, or Duration value */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                ],
                [
                    'fhirType'     => 'Period',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Period',
                    'jsonKey'      => 'valuePeriod',
                ],
                [
                    'fhirType'     => 'Duration',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration',
                    'jsonKey'      => 'valueDuration',
                ],
            ],
        )]
        public DateTimePrimitive|Period|Duration|null $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
