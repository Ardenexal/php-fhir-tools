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
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-glstring
 *
 * @description glstring.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-glstring', fhirVersion: 'R5')]
class GLStringExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UriPrimitive|null urlSlice glstring.url */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $urlSlice = null,
        /** @var StringPrimitive|null text glstring.text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $text = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->urlSlice !== null) {
            $subExtensions[] = new Extension(url: 'url', value: $this->urlSlice);
        }
        if ($this->text !== null) {
            $subExtensions[] = new Extension(url: 'text', value: $this->text);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-glstring',
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
        $urlSlice = null;
        $text     = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'url' && $ext->value instanceof UriPrimitive) {
                $urlSlice = $ext->value;
            }
            if ($extUrl === 'text' && $ext->value instanceof StringPrimitive) {
                $text = $ext->value;
            }
        }

        return new static($urlSlice, $text, $id);
    }
}
