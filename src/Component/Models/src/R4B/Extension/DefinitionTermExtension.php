<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-definitionTerm
 *
 * @description Specifies a term and its associated definition.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-definitionTerm', fhirVersion: 'R4B')]
class DefinitionTermExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive term Term being defined */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $term,
        /** @var MarkdownPrimitive definition Definition of the term */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public MarkdownPrimitive $definition,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'term', value: $this->term);
        $subExtensions[] = new Extension(url: 'definition', value: $this->definition);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-definitionTerm',
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
        $term       = null;
        $definition = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'term' && $ext->value instanceof StringPrimitive) {
                $term = $ext->value;
            }
            if ($extUrl === 'definition' && $ext->value instanceof MarkdownPrimitive) {
                $definition = $ext->value;
            }
        }

        if ($term === null) {
            throw new \InvalidArgumentException('Required sub-extension "term" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($definition === null) {
            throw new \InvalidArgumentException('Required sub-extension "definition" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($term, $definition, $id);
    }
}
