<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/textLink
 *
 * @description Used to denote which portions of the narrative are linked to (usually, generated from) structured data in resources. This information might be used in several different ways, including translating and regenerating narrative in applications that are using/presenting the narrative. Note that there are two related extensions for linking data and narrative: [originalText](StructureDefinition-originalText.html) and [narrativeLink](StructureDefinition-narrativeLink.html).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/textLink', fhirVersion: 'R5')]
class TextLinkExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UriPrimitive data Unique identifier */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public UriPrimitive $data,
        /** @var array<StringPrimitive> htmlid Unique identifier */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $htmlid = [],
        /** @var StringPrimitive|null selector FHIRPath that selects a subset of the identified data */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $selector = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'data', value: $this->data);
        foreach ($this->htmlid as $v) {
            $subExtensions[] = new Extension(url: 'htmlid', value: $v);
        }
        if ($this->selector !== null) {
            $subExtensions[] = new Extension(url: 'selector', value: $this->selector);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/textLink',
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
        $htmlid   = [];
        $data     = null;
        $selector = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'htmlid' && $ext->value instanceof StringPrimitive) {
                $htmlid[] = $ext->value;
            }
            if ($extUrl === 'data' && $ext->value instanceof UriPrimitive) {
                $data = $ext->value;
            }
            if ($extUrl === 'selector' && $ext->value instanceof StringPrimitive) {
                $selector = $ext->value;
            }
        }

        if ($data === null) {
            throw new \InvalidArgumentException('Required sub-extension "data" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($htmlid, $data, $selector, $id);
    }
}
