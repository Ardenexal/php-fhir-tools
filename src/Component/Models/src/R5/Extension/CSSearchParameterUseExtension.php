<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-use
 *
 * @description This extension defines if a search parameter is only allowed in certain contexts
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-use', fhirVersion: 'R5')]
class CSSearchParameterUseExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var bool required If this search parameter can use used in standalone search. */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public bool $required,
        /** @var bool allowInclude If this search parameter can use used in _include search. */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public bool $allowInclude,
        /** @var bool allowRevinclude If this search parameter can use used in _revinclude search. */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public bool $allowRevinclude,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'required', value: $this->required);
        $subExtensions[] = new Extension(url: 'allow-include', value: $this->allowInclude);
        $subExtensions[] = new Extension(url: 'allow-revinclude', value: $this->allowRevinclude);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-use',
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
        $required        = null;
        $allowInclude    = null;
        $allowRevinclude = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'required' && is_bool($ext->value)) {
                $required = $ext->value;
            }
            if ($extUrl === 'allow-include' && is_bool($ext->value)) {
                $allowInclude = $ext->value;
            }
            if ($extUrl === 'allow-revinclude' && is_bool($ext->value)) {
                $allowRevinclude = $ext->value;
            }
        }

        if ($required === null) {
            throw new \InvalidArgumentException('Required sub-extension "required" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($allowInclude === null) {
            throw new \InvalidArgumentException('Required sub-extension "allow-include" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($allowRevinclude === null) {
            throw new \InvalidArgumentException('Required sub-extension "allow-revinclude" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($required, $allowInclude, $allowRevinclude, $id);
    }
}
