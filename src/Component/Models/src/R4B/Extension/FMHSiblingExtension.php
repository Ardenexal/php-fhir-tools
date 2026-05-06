<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @author HL7 International / Clinical Genomics
 *
 * @see http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling
 *
 * @description Identifies a sibling of the relative.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling', fhirVersion: 'R4B')]
class FMHSiblingExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type sibling | brother | sister | etc. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $type,
        /** @var Reference reference Link to sibling relative(s) */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $reference,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-sibling',
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
        $type      = null;
        $reference = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'reference' && $ext->value instanceof Reference) {
                $reference = $ext->value;
            }
        }

        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($reference === null) {
            throw new \InvalidArgumentException('Required sub-extension "reference" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($type, $reference, $id);
    }
}
