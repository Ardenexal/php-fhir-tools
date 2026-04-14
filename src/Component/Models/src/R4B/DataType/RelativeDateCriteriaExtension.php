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
 * @see http://hl7.org/fhir/StructureDefinition/relative-date
 *
 * @description Specifies that a date is relative to some event. The event happens [Duration] after [Event].
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/relative-date', fhirVersion: 'R4B')]
class RelativeDateCriteriaExtension extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Reference event Event that the date is relative to */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $event,
        /** @var CodePrimitive relationship before-start | before | before-end | concurrent-with-start | concurrent | concurrent-with-end | after-start | after | after-end */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $relationship,
        /** @var Duration offset Duration after the event */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Duration $offset,
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

        return new static($event, $relationship, $offset, $id);
    }
}
