<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/translation
 *
 * @description Language translation from base language of resource to another language.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/translation', fhirVersion: 'R4B')]
class TranslationExtension extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive lang Code for Language */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $lang,
        /** @var StringPrimitive content Content in other Language */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive $content,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'lang', value: $this->lang);
        $subExtensions[] = new Extension(url: 'content', value: $this->content);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/translation',
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
        $lang    = null;
        $content = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'lang' && $ext->value instanceof CodePrimitive) {
                $lang = $ext->value;
            }
            if ($extUrl === 'content' && $ext->value instanceof StringPrimitive) {
                $content = $ext->value;
            }
        }

        return new static($lang, $content, $id);
    }
}
