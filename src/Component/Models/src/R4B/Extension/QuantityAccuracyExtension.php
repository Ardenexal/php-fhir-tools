<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/quantity-accuracy
 *
 * @description The absolute of the maximum deviation of the actual value from the reported value.
 *
 * Accuracy is particularly applicable to measured or observed values, and represents the maximum deviation over the entire range of measurement. When applied to a DeviceMetric, it expresses the maximum deviation of values reported by the metric. The reported observed value should be precise enough to reflect this accuracy, for example an observed value of 3.2 kg with an accuracy of 0.04 kg would be nonsensical. On the other hand, an observed value of 3.02 kg with an accuracy of 0.04 kg would indicate that the actual value is in the range of 3.02±0.04 kg or [2.98, 3.06] kg.
 *
 * Accuracy is usually in the same units as the reported value. Accuracy valueQuantity units SHOULD be provided for clarity; if omitted, they are implied to be the same as the reported value's units (or the DeviceMetric units).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/quantity-accuracy', fhirVersion: 'R4B')]
class QuantityAccuracyExtension extends Extension
{
    public function __construct(
        /** @var Quantity|null valueQuantity Absolute maximum deviation of the reported value from the actual value */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $valueQuantity = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/quantity-accuracy',
            value: $this->valueQuantity,
        );
    }
}
