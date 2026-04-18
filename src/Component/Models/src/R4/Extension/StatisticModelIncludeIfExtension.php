<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/statistic-model-include-if
 *
 * @description The condition under which the variable (or modelCharacteristic) will be included.
 *
 * The extension can be applied to Evidence.statistic.modelCharacteristic to describe when that modelCharacteristic is included in the statistical model, or can be applied to Evidence.statistic.modelCharacteristic.variable to describe when that variable is included in the adjusted analysis.
 *
 * This extension requires two elements, an attribute and a value[x]. The interpretation of the extension instance is that when the attribute VALUE matches the value[x] VALUE, then the corresponding model characteristic (or variable) element shall be included in the model.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/statistic-model-include-if', fhirVersion: 'R4')]
class StatisticModelIncludeIfExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept attribute Extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $attribute,
        /** @var bool valueSlice Extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public bool $valueSlice,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'attribute', value: $this->attribute);
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/statistic-model-include-if',
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
        $attribute  = null;
        $valueSlice = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'attribute' && $ext->value instanceof CodeableConcept) {
                $attribute = $ext->value;
            }
            if ($extUrl === 'value' && is_bool($ext->value)) {
                $valueSlice = $ext->value;
            }
        }

        if ($attribute === null) {
            throw new \InvalidArgumentException('Required sub-extension "attribute" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($attribute, $valueSlice, $id);
    }
}
