<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Terminology Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/terminology-resource-identifier-metadata
 *
 * @description Additional metadata in identifier elements for terminology resources.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/terminology-resource-identifier-metadata', fhirVersion: 'R5')]
class TxResourceIdentifierMetadataExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var bool|null preferred Whether this an identifier that should be used */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $preferred = null,
        /** @var bool|null authoritative Whether this identifier is considered to be authoritative */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $authoritative = null,
        /** @var StringPrimitive|null comment Text to explain the use of the additional metadata values. */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $comment = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->preferred !== null) {
            $subExtensions[] = new Extension(url: 'preferred', value: $this->preferred);
        }
        if ($this->authoritative !== null) {
            $subExtensions[] = new Extension(url: 'authoritative', value: $this->authoritative);
        }
        if ($this->comment !== null) {
            $subExtensions[] = new Extension(url: 'comment', value: $this->comment);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/terminology-resource-identifier-metadata',
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
        $preferred     = null;
        $authoritative = null;
        $comment       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'preferred' && is_bool($ext->value)) {
                $preferred = $ext->value;
            }
            if ($extUrl === 'authoritative' && is_bool($ext->value)) {
                $authoritative = $ext->value;
            }
            if ($extUrl === 'comment' && $ext->value instanceof StringPrimitive) {
                $comment = $ext->value;
            }
        }

        return new static($preferred, $authoritative, $comment, $id);
    }
}
