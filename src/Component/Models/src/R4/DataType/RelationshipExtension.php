<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/goal-relationship
 *
 * @description Establishes a relationship between this goal and other goals.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/goal-relationship', fhirVersion: 'R4')]
class RelationshipExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type predecessor | successor | replacement | other */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type,
        /** @var Reference target Related goal */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $target,
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

        return new static($type, $target, $id);
    }
}
