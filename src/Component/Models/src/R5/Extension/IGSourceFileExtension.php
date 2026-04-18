<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/implementationguide-sourceFile
 *
 * @description Identifies files used as part of the the publication process other than resources referenced in definition.resource.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/implementationguide-sourceFile', fhirVersion: 'R5')]
class IGSourceFileExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference file Location on server */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $file,
        /** @var StringPrimitive location Path for publisher */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $location,
        /** @var bool|null keepAsResource Use attachment or resource? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $keepAsResource = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'file', value: $this->file);
        $subExtensions[] = new Extension(url: 'location', value: $this->location);
        if ($this->keepAsResource !== null) {
            $subExtensions[] = new Extension(url: 'keepAsResource', value: $this->keepAsResource);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/implementationguide-sourceFile',
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
        $file           = null;
        $location       = null;
        $keepAsResource = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'file' && $ext->value instanceof Reference) {
                $file = $ext->value;
            }
            if ($extUrl === 'location' && $ext->value instanceof StringPrimitive) {
                $location = $ext->value;
            }
            if ($extUrl === 'keepAsResource' && is_bool($ext->value)) {
                $keepAsResource = $ext->value;
            }
        }

        if ($file === null) {
            throw new \InvalidArgumentException('Required sub-extension "file" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($location === null) {
            throw new \InvalidArgumentException('Required sub-extension "location" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($file, $location, $keepAsResource, $id);
    }
}
