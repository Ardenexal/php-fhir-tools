<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/quantity-confidenceInterval
 *
 * @description The range within which, at the given level of confidence, the actual value resides. For example, a nominal value (from the context Observation.value) of 4.3 may be reported, but this extension may be used to indicate that the reporting device is only 95% confident that the actual value is in the range of 3.1 to 5.5.
 *
 * The reported value, if present, should be precise enough to reflect the confidence interval. For example, a nominal quantity of 3.2 kg with a confidence interval of [3.21, 3.27] kg would be nonsensical. On the other hand, a reported value of 3.24 kg with a 95% confidence interval of [3.21, 3.27] kg would indicate a 95% confidence that the actual value is in that range.
 *
 * The reported value need not be at the center of the confidence interval; for example, if the values don't follow a normal distribution. The context Observation.value SHOULD be present when this extension is used, due to the risk that systems unfamiliar with (or ignoring) this extension may be unable to process a valueless quantity.
 *
 * The interval range low and high units are usually the same as the reported value's units. Confidence interval range low and high units SHOULD be provided for clarity; if omitted, they are implied to be the same as the reported value's units.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/quantity-confidenceInterval', fhirVersion: 'R4')]
class QuantityConfidenceIntervalExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var string confidence The confidence level in percent (0..100) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public string $confidence,
        /** @var Range interval Range in which actual value is expected to reside */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public Range $interval,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'confidence', value: $this->confidence);
        $subExtensions[] = new Extension(url: 'interval', value: $this->interval);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/quantity-confidenceInterval',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $confidence = null;
        $interval   = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'confidence' && is_string($ext->value)) {
                $confidence = $ext->value;
            }
            if ($extUrl === 'interval' && $ext->value instanceof Range) {
                $interval = $ext->value;
            }
        }

        if ($confidence === null) {
            throw new \InvalidArgumentException('Required sub-extension "confidence" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($interval === null) {
            throw new \InvalidArgumentException('Required sub-extension "interval" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($confidence, $interval, $id);
    }
}
