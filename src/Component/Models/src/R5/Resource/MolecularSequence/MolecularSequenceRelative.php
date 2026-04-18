<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Range;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A sequence defined relative to another sequence.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative', fhirVersion: 'R5')]
class MolecularSequenceRelative extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null coordinateSystem Ways of identifying nucleotides or amino acids within a sequence */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $coordinateSystem = null,
        /** @var int|null ordinalPosition Indicates the order in which the sequence should be considered when putting multiple 'relative' elements together */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $ordinalPosition = null,
        /** @var Range|null sequenceRange Indicates the nucleotide range in the composed sequence when multiple 'relative' elements are used together */
        #[FhirProperty(fhirType: 'Range', propertyKind: 'complex')]
        public ?Range $sequenceRange = null,
        /** @var MolecularSequenceRelativeStartingSequence|null startingSequence A sequence used as starting sequence */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MolecularSequenceRelativeStartingSequence $startingSequence = null,
        /** @var array<MolecularSequenceRelativeEdit> edit Changes in sequence from the starting sequence */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MolecularSequence\MolecularSequenceRelativeEdit',
        )]
        public array $edit = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
