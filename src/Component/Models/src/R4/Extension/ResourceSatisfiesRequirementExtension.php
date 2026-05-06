<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/satisfies-requirement
 *
 * @description References a requirement that this element satisfies. This might be referenced at the resource level by a profile, operation definition, etc.  However, it could also point from a specific code in a value set, an interaction or search parameter in a CapabilityStatement, an action in a PlanDefinition, etc. to the requirement satisfied by that specific portion of the resource. Note that this extension is only used as part of the IG publication tooling process. Use the [Requirements extension](StructureDefinition-satisfies-requirement.html) extension for use outside the IG publishing framework.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/satisfies-requirement', fhirVersion: 'R4')]
class ResourceSatisfiesRequirementExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CanonicalPrimitive reference Source reference. */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public CanonicalPrimitive $reference,
        /** @var array<IdPrimitive> key Key that identifies requirement. */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isArray: true)]
        public array $key = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'reference', value: $this->reference);
        foreach ($this->key as $v) {
            $subExtensions[] = new Extension(url: 'key', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/satisfies-requirement',
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
        $reference = null;
        $key       = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'reference' && $ext->value instanceof CanonicalPrimitive) {
                $reference = $ext->value;
            }
            if ($extUrl === 'key' && $ext->value instanceof IdPrimitive) {
                $key[] = $ext->value;
            }
        }

        if ($reference === null) {
            throw new \InvalidArgumentException('Required sub-extension "reference" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($reference, $key, $id);
    }
}
