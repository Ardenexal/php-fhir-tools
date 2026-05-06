<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-proficiency
 *
 * @description Proficiency level of the communication.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-proficiency', fhirVersion: 'R4')]
class PatProficiencyExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Coding|null level The proficiency level of the communication */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $level = null,
        /** @var array<Coding> type The proficiency type of the communication */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isArray: true)]
        public array $type = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->level !== null) {
            $subExtensions[] = new Extension(url: 'level', value: $this->level);
        }
        foreach ($this->type as $v) {
            $subExtensions[] = new Extension(url: 'type', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-proficiency',
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
        $level = null;
        $type  = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'level' && $ext->value instanceof Coding) {
                $level = $ext->value;
            }
            if ($extUrl === 'type' && $ext->value instanceof Coding) {
                $type[] = $ext->value;
            }
        }

        return new static($level, $type, $id);
    }
}
