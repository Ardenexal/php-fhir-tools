<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-parent
 *
 * @description Identifies a parent of the relative.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-parent', fhirVersion: 'R4')]
class ParentExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept type mother | father | adoptive mother | etc. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type,
        /** @var Reference reference Link to parent relative(s) */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $reference,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'type', value: $this->type);
        $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-parent',
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

        return new static($type, $reference, $id);
    }
}
