<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-alternate
 *
 * @description An additional code that may be used to represent the concept.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-alternate', fhirVersion: 'R4')]
class CSAlternateExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive code Code that represents the concept */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $code,
        /** @var Coding use Expected use of the code */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public Coding $use,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'code', value: $this->code);
        $subExtensions[] = new Extension(url: 'use', value: $this->use);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-alternate',
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
        $code = null;
        $use  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'code' && $ext->value instanceof CodePrimitive) {
                $code = $ext->value;
            }
            if ($extUrl === 'use' && $ext->value instanceof Coding) {
                $use = $ext->value;
            }
        }

        if ($code === null) {
            throw new \InvalidArgumentException('Required sub-extension "code" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($use === null) {
            throw new \InvalidArgumentException('Required sub-extension "use" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($code, $use, $id);
    }
}
