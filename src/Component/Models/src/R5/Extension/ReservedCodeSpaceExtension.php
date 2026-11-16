<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-reservedCodeSpace
 *
 * @description This extension is used to describe a set of concept codes that has been allocated by the code system steward for users to create their own codes.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-reservedCodeSpace', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'CodeSystem')]
class ReservedCodeSpaceExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive reservedCodeSpace Regular expression for the reserved codes */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $reservedCodeSpace,
        /** @var MarkdownPrimitive|null description Narrative explaining the set of codes */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'reservedCodeSpace', value: $this->reservedCodeSpace);
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-reservedCodeSpace',
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
        $reservedCodeSpace = null;
        $description       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'reservedCodeSpace' && $ext->value instanceof StringPrimitive) {
                $reservedCodeSpace = $ext->value;
            }
            if ($extUrl === 'description' && $ext->value instanceof MarkdownPrimitive) {
                $description = $ext->value;
            }
        }

        if ($reservedCodeSpace === null) {
            throw new \InvalidArgumentException('Required sub-extension "reservedCodeSpace" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new self($reservedCodeSpace, $description, $id);
    }
}
