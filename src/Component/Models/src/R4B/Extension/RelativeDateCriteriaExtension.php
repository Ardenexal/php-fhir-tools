<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/relative-date
 *
 * @description Specifies that a date is relative to some event. The event happens [Duration] after [Event].
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/relative-date', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'date')]
#[FHIRExtensionContext(type: 'element', expression: 'dateTime')]
class RelativeDateCriteriaExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference event Event that the date is relative to */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public Reference $event,
        /** @var CodePrimitive relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $relationship,
        /** @var Duration offset Duration after the event */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public Duration $offset,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'event', value: $this->event);
        $subExtensions[] = new Extension(url: 'relationship', value: $this->relationship);
        $subExtensions[] = new Extension(url: 'offset', value: $this->offset);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/relative-date',
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
        $event        = null;
        $relationship = null;
        $offset       = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'event' && $ext->value instanceof Reference) {
                $event = $ext->value;
            }
            if ($extUrl === 'relationship' && $ext->value instanceof CodePrimitive) {
                $relationship = $ext->value;
            }
            if ($extUrl === 'offset' && $ext->value instanceof Duration) {
                $offset = $ext->value;
            }
        }

        if ($event === null) {
            throw new \InvalidArgumentException('Required sub-extension "event" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($relationship === null) {
            throw new \InvalidArgumentException('Required sub-extension "relationship" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($offset === null) {
            throw new \InvalidArgumentException('Required sub-extension "offset" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($event, $relationship, $offset, $id);
    }
}
