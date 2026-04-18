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
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/goal-acceptance
 *
 * @description Information about the acceptance and relative priority assigned to the goal by the patient, practitioners and other stake-holders. This acceptance extension was elevated to the base Goal resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/goal-acceptance', fhirVersion: 'R5')]
class GoalAcceptanceExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference individual Individual whose acceptance is reflected */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $individual,
        /** @var CodePrimitive|null status agree | disagree | pending */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $status = null,
        /** @var CodeableConcept|null priority Priority of goal for individual */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $priority = null,
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

        if ($individual === null) {
            throw new \InvalidArgumentException('Required sub-extension "individual" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($individual, $status, $priority, $id);
    }
}
