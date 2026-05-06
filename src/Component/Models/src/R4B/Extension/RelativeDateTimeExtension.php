<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-relativeDateTime
 *
 * @description A date/time value that is determined based on a duration offset from a target event. DEPRECATED: This extension has been deprecated in favor of the new relative-time extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-relativeDateTime', fhirVersion: 'R4B')]
class RelativeDateTimeExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference target Relative to what event */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $target,
        /** @var StringPrimitive targetPath Relative to which element on the event */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $targetPath,
        /** @var CodePrimitive relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $relationship,
        /** @var Duration offset How long */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public Duration $offset,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'target', value: $this->target);
        $subExtensions[] = new Extension(url: 'targetPath', value: $this->targetPath);
        $subExtensions[] = new Extension(url: 'relationship', value: $this->relationship);
        $subExtensions[] = new Extension(url: 'offset', value: $this->offset);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-relativeDateTime',
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
        $target       = null;
        $targetPath   = null;
        $relationship = null;
        $offset       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'target' && $ext->value instanceof Reference) {
                $target = $ext->value;
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

        if ($target === null) {
            throw new \InvalidArgumentException('Required sub-extension "target" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($targetPath === null) {
            throw new \InvalidArgumentException('Required sub-extension "targetPath" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($relationship === null) {
            throw new \InvalidArgumentException('Required sub-extension "relationship" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($offset === null) {
            throw new \InvalidArgumentException('Required sub-extension "offset" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($target, $targetPath, $relationship, $offset, $id);
    }
}
