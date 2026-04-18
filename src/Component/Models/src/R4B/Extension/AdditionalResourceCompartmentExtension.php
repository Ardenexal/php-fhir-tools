<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/additional-resource-compartment
 *
 * @description Allows the definition of an additional resource to indicate the resource is a candidate for inclusion in a compartment by the implementing server.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/additional-resource-compartment', fhirVersion: 'R4B')]
class AdditionalResourceCompartmentExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CanonicalPrimitive compartment Reference to the compartment this resource would like to be included in */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public CanonicalPrimitive $compartment,
        /** @var array<StringPrimitive> param Search Parameter Name, or chained parameters */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $param = [],
        /** @var StringPrimitive|null documentation Additional documentation about the resource and compartment */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $documentation = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'compartment', value: $this->compartment);
        foreach ($this->param as $v) {
            $subExtensions[] = new Extension(url: 'param', value: $v);
        }
        if ($this->documentation !== null) {
            $subExtensions[] = new Extension(url: 'documentation', value: $this->documentation);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/additional-resource-compartment',
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
        $compartment   = null;
        $param         = [];
        $documentation = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'compartment' && $ext->value instanceof CanonicalPrimitive) {
                $compartment = $ext->value;
            }
            if ($extUrl === 'param' && $ext->value instanceof StringPrimitive) {
                $param[] = $ext->value;
            }
            if ($extUrl === 'documentation' && $ext->value instanceof StringPrimitive) {
                $documentation = $ext->value;
            }
        }

        if ($compartment === null) {
            throw new \InvalidArgumentException('Required sub-extension "compartment" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($compartment, $param, $documentation, $id);
    }
}
