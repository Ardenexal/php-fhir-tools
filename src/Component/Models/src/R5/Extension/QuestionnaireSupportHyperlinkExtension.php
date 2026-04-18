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
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-supportHyperlink
 *
 * @description A labeled hyperlink for display alongside a questionnaire item, providing supporting guidance to the user.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-supportHyperlink', fhirVersion: 'R5')]
class QuestionnaireSupportHyperlinkExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive label The hyperlink display text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $label,
        /** @var UriPrimitive link The hyperlink URL */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public UriPrimitive $link,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'label', value: $this->label);
        $subExtensions[] = new Extension(url: 'link', value: $this->link);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-supportHyperlink',
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
        $label = null;
        $link  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'label' && $ext->value instanceof StringPrimitive) {
                $label = $ext->value;
            }
            if ($extUrl === 'link' && $ext->value instanceof UriPrimitive) {
                $link = $ext->value;
            }
        }

        if ($label === null) {
            throw new \InvalidArgumentException('Required sub-extension "label" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($link === null) {
            throw new \InvalidArgumentException('Required sub-extension "link" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($label, $link, $id);
    }
}
