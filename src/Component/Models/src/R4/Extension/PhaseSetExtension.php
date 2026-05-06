<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet
 *
 * @description Phase set information.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet', fhirVersion: 'R4')]
class PhaseSetExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UriPrimitive|null idSlice Phase set ID */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $idSlice = null,
        /** @var array<Reference> molecularSequence Phase set sequence */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $molecularSequence = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->idSlice !== null) {
            $subExtensions[] = new Extension(url: 'Id', value: $this->idSlice);
        }
        foreach ($this->molecularSequence as $v) {
            $subExtensions[] = new Extension(url: 'MolecularSequence', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet',
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
        $idSlice           = null;
        $molecularSequence = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'Id' && $ext->value instanceof UriPrimitive) {
                $idSlice = $ext->value;
            }
            if ($extUrl === 'MolecularSequence' && $ext->value instanceof Reference) {
                $molecularSequence[] = $ext->value;
            }
        }

        return new static($idSlice, $molecularSequence, $id);
    }
}
