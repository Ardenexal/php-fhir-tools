<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/package-source
 *
 * @description Specifies the package in which an artifact is or was included.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/package-source', fhirVersion: 'R4B')]
class PackageSourceExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var IdPrimitive packageId NPM-style package id */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public IdPrimitive $packageId,
        /** @var StringPrimitive|null version Package version */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $version = null,
        /** @var UriPrimitive|null uri Package uri */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $uri = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'packageId', value: $this->packageId);
        if ($this->version !== null) {
            $subExtensions[] = new Extension(url: 'version', value: $this->version);
        }
        if ($this->uri !== null) {
            $subExtensions[] = new Extension(url: 'uri', value: $this->uri);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/package-source',
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
        $packageId = null;
        $version   = null;
        $uri       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'packageId' && $ext->value instanceof IdPrimitive) {
                $packageId = $ext->value;
            }
            if ($extUrl === 'version' && $ext->value instanceof StringPrimitive) {
                $version = $ext->value;
            }
            if ($extUrl === 'uri' && $ext->value instanceof UriPrimitive) {
                $uri = $ext->value;
            }
        }

        if ($packageId === null) {
            throw new \InvalidArgumentException('Required sub-extension "packageId" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($packageId, $version, $uri, $id);
    }
}
