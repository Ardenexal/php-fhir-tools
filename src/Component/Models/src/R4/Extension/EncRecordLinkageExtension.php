<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/encounter-recordLinkage
 *
 * @description This extension can be used to link two encounters that are related. This extension may be used when you want to provide a directional relationship between two encounters. If you just want to just list a set of related encounters without directional relationship, use the Linkage resource instead.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/encounter-recordLinkage', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'Encounter')]
class EncRecordLinkageExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference other The other encounter in the link */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $other,
        /** @var CodePrimitive type replaces | replaced-by */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $type,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'other', value: $this->other);
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/encounter-recordLinkage',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<Extension> $subExtensions
     * @param string|null      $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): self
    {
        $other = null;
        $type  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'other' && $ext->value instanceof Reference) {
                $other = $ext->value;
            }
            if ($extUrl === 'type' && $ext->value instanceof CodePrimitive) {
                $type = $ext->value;
            }
        }

        if ($other === null) {
            throw new \InvalidArgumentException('Required sub-extension "other" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new self($other, $type, $id);
    }
}
