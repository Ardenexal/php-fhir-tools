<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/relative-date
 *
 * @description Specifies that a date is relative to some event. The event happens [Duration] after [Event].
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/relative-date', fhirVersion: 'R4B')]
class RelativeDateCriteriaExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference targetReference Specific event that the date is relative to */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $targetReference,
        /** @var CodeableConcept targetCode Kind of event that the date is relative to */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $targetCode,
        /** @var Duration offset Duration before or after the event */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public Duration $offset,
        /** @var StringPrimitive|null targetPath Relative to which element on the event */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $targetPath = null,
        /** @var CodePrimitive|null relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $relationship = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'targetReference', value: $this->targetReference);
        $subExtensions[] = new Extension(url: 'targetCode', value: $this->targetCode);
        $subExtensions[] = new Extension(url: 'offset', value: $this->offset);
        if ($this->targetPath !== null) {
            $subExtensions[] = new Extension(url: 'targetPath', value: $this->targetPath);
        }
        if ($this->relationship !== null) {
            $subExtensions[] = new Extension(url: 'relationship', value: $this->relationship);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/relative-date',
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
        $targetReference = null;
        $targetCode      = null;
        $targetPath      = null;
        $relationship    = null;
        $offset          = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'targetReference' && $ext->value instanceof Reference) {
                $targetReference = $ext->value;
            }
            if ($extUrl === 'targetCode' && $ext->value instanceof CodeableConcept) {
                $targetCode = $ext->value;
            }
            if ($extUrl === 'targetPath' && $ext->value instanceof StringPrimitive) {
                $targetPath = $ext->value;
            }
            if ($extUrl === 'relationship' && $ext->value instanceof CodePrimitive) {
                $relationship = $ext->value;
            }
            if ($extUrl === 'offset' && $ext->value instanceof Duration) {
                $offset = $ext->value;
            }
        }

        if ($targetReference === null) {
            throw new \InvalidArgumentException('Required sub-extension "targetReference" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($targetCode === null) {
            throw new \InvalidArgumentException('Required sub-extension "targetCode" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($offset === null) {
            throw new \InvalidArgumentException('Required sub-extension "offset" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($targetReference, $targetCode, $targetPath, $relationship, $offset, $id);
    }
}
