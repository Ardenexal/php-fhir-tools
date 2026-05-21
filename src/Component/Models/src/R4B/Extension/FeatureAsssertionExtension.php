<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/feature-assertion
 *
 * @description This extension asserts that the data in a resource was authored (collected/handled/created/transformed) by an application that claims conformance to the definition of a feature. Note that 'authoring' is often a client function, but that is not always the case.
 *
 *   For further information about features, see the [Application Feature Framework Implementation Guide](https://build.fhir.org/ig/HL7/capstmt/specification.html).
 *
 *   As an example of the kind of use this extension might support, an application could choose to only use value set  expansions that are explicitly labeled as 'prepared under the conformance rules defined in the [CRMI implementation guide](https://build.fhir.org/ig/HL7/crmi-ig).
 *
 *   This extension is a statement about the provenance of a resource and is placed in the resource about which the assertion is made. The assertion SHOULD be removed when the resource data is altered (it may be replaced by a new assertion). See the related extension for [declaring feature conformance in the Provenance](StructureDefinition-target-feature-assertion.html)'.
 *
 *   This assertion is often used to drive processing rules associated with the trustworthiness of the data in  the resource. Applications/specifications/workflows that make use of this assertion should carefully consider the integrity of the chain of handling from the source the processing before choosing to trust the assertion.
 *
 *   A more complex alternative to this profile is to use the [[[http://hl7.org/fhir/StructureDefinition/obligations-profile]]] extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/feature-assertion', fhirVersion: 'R4B')]
class FeatureAsssertionExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UriPrimitive definition Identifies the feature definition */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public UriPrimitive $definition,
        /** @var Base64BinaryPrimitive valueSlice The value of the feature */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive')]
        public Base64BinaryPrimitive $valueSlice,
        /** @var array<Base64BinaryPrimitive> property Provides a value for a qualifier of the feature */
        #[FhirProperty(fhirType: 'base64Binary', propertyKind: 'primitive', isArray: true)]
        public array $property = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'definition', value: $this->definition);
        $subExtensions[] = new Extension(url: 'value', value: $this->valueSlice);
        foreach ($this->property as $v) {
            $subExtensions[] = new Extension(url: 'property', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/feature-assertion',
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
        $definition = null;
        $valueSlice = null;
        $property   = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'definition' && $ext->value instanceof UriPrimitive) {
                $definition = $ext->value;
            }
            if ($extUrl === 'value' && $ext->value instanceof Base64BinaryPrimitive) {
                $valueSlice = $ext->value;
            }
            if ($extUrl === 'property' && $ext->value instanceof Base64BinaryPrimitive) {
                $property[] = $ext->value;
            }
        }

        if ($definition === null) {
            throw new \InvalidArgumentException('Required sub-extension "definition" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($valueSlice === null) {
            throw new \InvalidArgumentException('Required sub-extension "value" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($definition, $valueSlice, $property, $id);
    }
}
