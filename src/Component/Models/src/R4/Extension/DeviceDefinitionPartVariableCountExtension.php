<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition-partVariableCount
 *
 * @description Indicates the minimum and maximum permissible quantity of the part in the defined device, without regard for how the presence of other parts impact those values.
 *
 * For example, a device may have connectors for up to three sensors of different types. Each sensor type definition would be referenced in `hasPart.reference`; it would have no `hasPart.count` value, but instead use this extension on `hasPart.count` and setting a minimum count of zero, and maximum of one.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition-partVariableCount', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceDefinition.hasPart.count')]
#[FHIRContextInvariant(expression: '$this.hasValue().not()')]
class DeviceDefinitionPartVariableCountExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UnsignedIntPrimitive minimum The minimum count of the part in the device */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public UnsignedIntPrimitive $minimum,
        /** @var UnsignedIntPrimitive maximum The maximum count of the part in the device */
        #[FhirProperty(fhirType: 'unsignedInt', propertyKind: 'primitive')]
        public UnsignedIntPrimitive $maximum,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'minimum', value: $this->minimum);
        $subExtensions[] = new Extension(url: 'maximum', value: $this->maximum);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition-partVariableCount',
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
        $minimum = null;
        $maximum = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'minimum' && $ext->value instanceof UnsignedIntPrimitive) {
                $minimum = $ext->value;
            }
            if ($extUrl === 'maximum' && $ext->value instanceof UnsignedIntPrimitive) {
                $maximum = $ext->value;
            }
        }

        if ($minimum === null) {
            throw new \InvalidArgumentException('Required sub-extension "minimum" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($maximum === null) {
            throw new \InvalidArgumentException('Required sub-extension "maximum" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($minimum, $maximum, $id);
    }
}
