<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/version-specific-value
 *
 * @description Provides an alternative value for the element carrying this extension that is the correct value for a particular range of FHIR versions other than the default value. This extension is found in contexts where a definition is applying to more than one version, usually defining extensions, and should only be used in context that clearly document how a cross-version definition is used. While there are no limitations to where this extension can be used, known uses are: StructureDefinition.context, ElementDefinition.type, ElementDefinition.additionalBinding and ...
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/version-specific-value', fhirVersion: 'R5')]
class VersionSpecificValueExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive valueSlice Starting Version */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $valueSlice,
        /** @var CodePrimitive startFhirVersion Starting Version */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $startFhirVersion,
        /** @var CodePrimitive endFhirVersion Ending Version */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $endFhirVersion,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        $subExtensions[] = new Extension(url: 'startFhirVersion', value: $this->startFhirVersion);
        $subExtensions[] = new Extension(url: 'endFhirVersion', value: $this->endFhirVersion);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/version-specific-value',
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
        $valueSlice       = null;
        $startFhirVersion = null;
        $endFhirVersion   = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'value' && $ext->value instanceof StringPrimitive) {
                $valueSlice = $ext->value;
            }
            if ($extUrl === 'startFhirVersion' && $ext->value instanceof CodePrimitive) {
                $startFhirVersion = $ext->value;
            }
            if ($extUrl === 'endFhirVersion' && $ext->value instanceof CodePrimitive) {
                $endFhirVersion = $ext->value;
            }
        }

        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($startFhirVersion === null) {
            throw new \InvalidArgumentException('Required sub-extension "startFhirVersion" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($endFhirVersion === null) {
            throw new \InvalidArgumentException('Required sub-extension "endFhirVersion" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($valueSlice, $startFhirVersion, $endFhirVersion, $id);
    }
}
