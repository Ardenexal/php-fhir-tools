<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-assocConceptProp
 *
 * @description Concept Properties that are associated with this Code System or Value Set Version
 */
#[FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-assocConceptProp', fhirVersion: 'R4')]
class AssociatedConceptPropertyExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive name Property name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $name,
        /** @var StringPrimitive valueSlice Property value */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $valueSlice,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'name', value: $this->name);
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-assocConceptProp',
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
        $name       = null;
        $valueSlice = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'name' && $ext->value instanceof StringPrimitive) {
                $name = $ext->value;
            }
            if ($extUrl === 'value' && $ext->value instanceof StringPrimitive) {
                $valueSlice = $ext->value;
            }
        }

        if ($name === null) {
            throw new \InvalidArgumentException('Required sub-extension "name" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($name, $valueSlice, $id);
    }
}
