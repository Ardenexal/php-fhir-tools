<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-valueFilter
 *
 * @description Allows additional value-based filters to be specified as part of a data requirement.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-valueFilter', fhirVersion: 'R4B')]
class ValueFilterExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive comparator Extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $comparator,
        /** @var StringPrimitive|null path Extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $path = null,
        /** @var StringPrimitive|null searchParam Extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $searchParam = null,
        /** @var bool|null valueSlice Extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueSlice = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'comparator', value: $this->comparator);
        if ($this->path !== null) {
            $subExtensions[] = new Extension(url: 'path', value: $this->path);
        }
        if ($this->searchParam !== null) {
            $subExtensions[] = new Extension(url: 'searchParam', value: $this->searchParam);
        }
        if ($this->valueSlice !== null) {
            $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-valueFilter',
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
        $path        = null;
        $searchParam = null;
        $comparator  = null;
        $valueSlice  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'path' && $ext->value instanceof StringPrimitive) {
                $path = $ext->value;
            }
            if ($extUrl === 'searchParam' && $ext->value instanceof StringPrimitive) {
                $searchParam = $ext->value;
            }
            if ($extUrl === 'comparator' && $ext->value instanceof CodePrimitive) {
                $comparator = $ext->value;
            }
            if ($extUrl === 'value' && is_bool($ext->value)) {
                $valueSlice = $ext->value;
            }
        }

        if ($comparator === null) {
            throw new \InvalidArgumentException('Required sub-extension "comparator" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($path, $searchParam, $comparator, $valueSlice, $id);
    }
}
