<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-v2-subid
 *
 * @description A complex extension matching the structure of the V2 OG data type.  This is used in the v2-to-fhir mapping IG to capture and preserve the OBX-4 Sub-Id details.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-v2-subid', fhirVersion: 'R4B')]
class ObsV2SubIdExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null originalSubIdentifier Original Sub-Identifier */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $originalSubIdentifier = null,
        /** @var string|null group Group */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $group = null,
        /** @var string|null sequence Sequence */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $sequence = null,
        /** @var StringPrimitive|null identifier Identifier */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $identifier = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->originalSubIdentifier !== null) {
            $subExtensions[] = new Extension(url: 'original-sub-identifier', value: $this->originalSubIdentifier);
        }
        if ($this->group !== null) {
            $subExtensions[] = new Extension(url: 'group', value: $this->group);
        }
        if ($this->sequence !== null) {
            $subExtensions[] = new Extension(url: 'sequence', value: $this->sequence);
        }
        if ($this->identifier !== null) {
            $subExtensions[] = new Extension(url: 'identifier', value: $this->identifier);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-v2-subid',
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
        $originalSubIdentifier = null;
        $group                 = null;
        $sequence              = null;
        $identifier            = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'original-sub-identifier' && $ext->value instanceof StringPrimitive) {
                $originalSubIdentifier = $ext->value;
            }
            if ($extUrl === 'group' && is_string($ext->value)) {
                $group = $ext->value;
            }
            if ($extUrl === 'sequence' && is_string($ext->value)) {
                $sequence = $ext->value;
            }
            if ($extUrl === 'identifier' && $ext->value instanceof StringPrimitive) {
                $identifier = $ext->value;
            }
        }

        return new static($originalSubIdentifier, $group, $sequence, $identifier, $id);
    }
}
