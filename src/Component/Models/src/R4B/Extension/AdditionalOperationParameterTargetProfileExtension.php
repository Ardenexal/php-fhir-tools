<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/additional-operation-parameter-targetProfile
 *
 * @description Identifies an operation parameter with a type of Resource for which this resource is to be considered an additional permitted targetProfile.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/additional-operation-parameter-targetProfile', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition')]
class AdditionalOperationParameterTargetProfileExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CanonicalPrimitive urlSlice Operation whose parameter is supplemented */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public CanonicalPrimitive $urlSlice,
        /** @var CodePrimitive name Name of the parameter that is supplemented */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $name,
        /** @var CodePrimitive use in | out */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $use,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'url', value: $this->urlSlice);
        $subExtensions[] = new Extension(url: 'name', value: $this->name);
        $subExtensions[] = new Extension(url: 'use', value: $this->use);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/additional-operation-parameter-targetProfile',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<Extension> $subExtensions
     * @param string|null      $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): self
    {
        $urlSlice = null;
        $name     = null;
        $use      = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'url' && $ext->value instanceof CanonicalPrimitive) {
                $urlSlice = $ext->value;
            }
            if ($extUrl === 'name' && $ext->value instanceof CodePrimitive) {
                $name = $ext->value;
            }
            if ($extUrl === 'use' && $ext->value instanceof CodePrimitive) {
                $use = $ext->value;
            }
        }

        if ($urlSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "url" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($name === null) {
            throw new \InvalidArgumentException('Required sub-extension "name" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($use === null) {
            throw new \InvalidArgumentException('Required sub-extension "use" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new self($urlSlice, $name, $use, $id);
    }
}
