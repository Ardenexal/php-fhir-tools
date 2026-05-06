<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/timing-daysOfCycle
 *
 * @description Days of a possibly repeating cycle on which the action is to be performed. The cycle is defined by the first action with a timing element that is a parent of the daysOfCycle.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/timing-daysOfCycle', fhirVersion: 'R4B')]
class DaysOfCycleExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var array<int> day What day to perform */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $day = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        foreach ($this->day as $v) {
            $subExtensions[] = new Extension(url: 'day', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/timing-daysOfCycle',
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
        $day = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'day' && is_int($ext->value)) {
                $day[] = $ext->value;
            }
        }

        return new static($day, $id);
    }
}
