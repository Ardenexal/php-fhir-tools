<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/goal-relationship
 *
 * @description Establishes a relationship between this goal and other goals.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/goal-relationship', fhirVersion: 'R5')]
class GoalRelationshipExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type predecessor | successor | replacement | other */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $type,
        /** @var Reference target Related goal */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $target,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        $subExtensions[] = new Extension(url: 'target', value: $this->target);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/goal-relationship',
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
        $type   = null;
        $target = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
            if ($extUrl === 'target' && $ext->value instanceof Reference) {
                $target = $ext->value;
            }
        }

        if ($type === null) {
            throw new \InvalidArgumentException('Required sub-extension "type" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($target === null) {
            throw new \InvalidArgumentException('Required sub-extension "target" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($type, $target, $id);
    }
}
