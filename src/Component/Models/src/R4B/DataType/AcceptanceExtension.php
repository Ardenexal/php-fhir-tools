<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/goal-acceptance
 *
 * @description Information about the acceptance and relative priority assigned to the goal by the patient, practitioners and other stake-holders.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/goal-acceptance', fhirVersion: 'R4B')]
class AcceptanceExtension extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference individual Individual whose acceptance is reflected */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $individual,
        /** @var CodePrimitive|null status agree | disagree | pending */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $status = null,
        /** @var CodeableConcept|null priority Priority of goal for individual */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $priority = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'individual', value: $this->individual);
        if ($this->status !== null) {
            $subExtensions[] = new Extension(url: 'status', value: $this->status);
        }
        if ($this->priority !== null) {
            $subExtensions[] = new Extension(url: 'priority', value: $this->priority);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/goal-acceptance',
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
        $individual = null;
        $status     = null;
        $priority   = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'individual' && $ext->value instanceof Reference) {
                $individual = $ext->value;
            }
            if ($extUrl === 'status' && $ext->value instanceof CodePrimitive) {
                $status = $ext->value;
            }
            if ($extUrl === 'priority' && $ext->value instanceof CodeableConcept) {
                $priority = $ext->value;
            }
        }

        return new static($individual, $status, $priority, $id);
    }
}
