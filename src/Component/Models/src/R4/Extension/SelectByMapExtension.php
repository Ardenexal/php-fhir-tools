<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-select-by-map
 *
 * @description This extension indicates that in addition to the concepts directly selected (either included or excluded) in the include/exclude statement, any source codes that are mapped to target codes that are selected by the nominated ConceptMapare also selected. The filter property can be used to restrict which types of relationships are included.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-select-by-map', fhirVersion: 'R4')]
class SelectByMapExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CanonicalPrimitive map The canonical URL for the ConceptMap */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public CanonicalPrimitive $map,
        /** @var array<CodePrimitive> filter Include targets with this relationship in the selection */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $filter = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'map', value: $this->map);
        foreach ($this->filter as $v) {
            $subExtensions[] = new Extension(url: 'filter', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-select-by-map',
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
        $map    = null;
        $filter = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'map' && $ext->value instanceof CanonicalPrimitive) {
                $map = $ext->value;
            }
            if ($extUrl === 'filter' && $ext->value instanceof CodePrimitive) {
                $filter[] = $ext->value;
            }
        }

        if ($map === null) {
            throw new \InvalidArgumentException('Required sub-extension "map" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($map, $filter, $id);
    }
}
