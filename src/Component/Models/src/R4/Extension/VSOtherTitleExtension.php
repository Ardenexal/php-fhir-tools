<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-otherTitle
 *
 * @description Human readable titles for the valueset.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-otherTitle', fhirVersion: 'R4')]
class VSOtherTitleExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive title Human readable, short and specific */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $title,
        /** @var bool|null preferred Which Title is preferred for this language */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $preferred = null,
        /** @var CodePrimitive|null language Which language this title is in */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $language = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'title', value: $this->title);
        if ($this->preferred !== null) {
            $subExtensions[] = new Extension(url: 'preferred', value: $this->preferred);
        }
        if ($this->language !== null) {
            $subExtensions[] = new Extension(url: 'language', value: $this->language);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-otherTitle',
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
        $title     = null;
        $preferred = null;
        $language  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'title' && $ext->value instanceof StringPrimitive) {
                $title = $ext->value;
            }
            if ($extUrl === 'preferred' && is_bool($ext->value)) {
                $preferred = $ext->value;
            }
            if ($extUrl === 'language' && $ext->value instanceof CodePrimitive) {
                $language = $ext->value;
            }
        }

        if ($title === null) {
            throw new \InvalidArgumentException('Required sub-extension "title" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($title, $preferred, $language, $id);
    }
}
