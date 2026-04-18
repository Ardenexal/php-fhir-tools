<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-relatedRequirement
 *
 * @description Allows relationships to be established between data requirements. For example, a Medication data requirement that depends on a MedicationRequest data requirement.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-relatedRequirement', fhirVersion: 'R5')]
class RelatedRequirementExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive targetId What data requirement */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $targetId,
        /** @var StringPrimitive|null targetPath What element establishes the relationship */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $targetPath = null,
        /** @var StringPrimitive|null targetSearchParam What search parameter establishes the relationship */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $targetSearchParam = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'targetId', value: $this->targetId);
        if ($this->targetPath !== null) {
            $subExtensions[] = new Extension(url: 'targetPath', value: $this->targetPath);
        }
        if ($this->targetSearchParam !== null) {
            $subExtensions[] = new Extension(url: 'targetSearchParam', value: $this->targetSearchParam);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-relatedRequirement',
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
        $targetId          = null;
        $targetPath        = null;
        $targetSearchParam = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'targetId' && $ext->value instanceof StringPrimitive) {
                $targetId = $ext->value;
            }
            if ($extUrl === 'targetPath' && $ext->value instanceof StringPrimitive) {
                $targetPath = $ext->value;
            }
            if ($extUrl === 'targetSearchParam' && $ext->value instanceof StringPrimitive) {
                $targetSearchParam = $ext->value;
            }
        }

        if ($targetId === null) {
            throw new \InvalidArgumentException('Required sub-extension "targetId" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($targetId, $targetPath, $targetSearchParam, $id);
    }
}
