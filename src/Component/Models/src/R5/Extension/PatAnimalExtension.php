<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/patient-animal
 *
 * @description This patient is known to be an animal.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/patient-animal', fhirVersion: 'R5')]
class PatAnimalExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodeableConcept species The animal species.  E.g. Dog, Cow. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public CodeableConcept $species,
        /** @var CodeableConcept|null breed The animal breed.  E.g. Poodle, Angus. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $breed = null,
        /** @var CodeableConcept|null genderStatus The status of the animal's reproductive parts.  E.g. Neutered, Intact. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $genderStatus = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'species', value: $this->species);
        if ($this->breed !== null) {
            $subExtensions[] = new Extension(url: 'breed', value: $this->breed);
        }
        if ($this->genderStatus !== null) {
            $subExtensions[] = new Extension(url: 'genderStatus', value: $this->genderStatus);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/patient-animal',
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
        $species      = null;
        $breed        = null;
        $genderStatus = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'species' && $ext->value instanceof CodeableConcept) {
                $species = $ext->value;
            }
            if ($extUrl === 'breed' && $ext->value instanceof CodeableConcept) {
                $breed = $ext->value;
            }
            if ($extUrl === 'genderStatus' && $ext->value instanceof CodeableConcept) {
                $genderStatus = $ext->value;
            }
        }

        if ($species === null) {
            throw new \InvalidArgumentException('Required sub-extension "species" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($species, $breed, $genderStatus, $id);
    }
}
