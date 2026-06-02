<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\OrientationTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\StrandTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description A sequence that is used as a starting sequence to describe variants that are present in a sequence analyzed.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.relative.startingSequence', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'msq-5',
    severity: 'error',
    expression: 'chromosome.exists() = genomeAssembly.exists()',
    human: 'Both genomeAssembly and chromosome must be both contained if either one of them is contained',
)]
#[FHIRPathInvariant(
    key: 'msq-6',
    severity: 'error',
    expression: 'genomeAssembly.exists() xor sequence.exists()',
    human: 'Have and only have one of the following elements in startingSequence: 1. genomeAssembly; 2 sequence',
)]
class MolecularSequenceRelativeStartingSequence extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null genomeAssembly The genome assembly used for starting sequence, e.g. GRCh38 */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://loinc.org/LL1040-6/', strength: 'extensible')]
        public ?CodeableConcept $genomeAssembly = null,
        /** @var CodeableConcept|null chromosome Chromosome Identifier */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://loinc.org/LL2938-0/|5.0.0', strength: 'required')]
        public ?CodeableConcept $chromosome = null,
        /** @var CodeableConcept|StringPrimitive|string|Reference|null sequence The reference sequence that represents the starting sequence */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'CodeableConcept',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
                    'jsonKey'      => 'sequenceCodeableConcept',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'sequenceString',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
                    'jsonKey'      => 'sequenceReference',
                ],
            ],
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/MolecularSequence'])]
        public CodeableConcept|StringPrimitive|string|Reference|null $sequence = null,
        /** @var int|null windowStart Start position of the window on the starting sequence */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $windowStart = null,
        /** @var int|null windowEnd End position of the window on the starting sequence */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $windowEnd = null,
        /** @var OrientationTypeType|null orientation sense | antisense */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/orientation-type|5.0.0', strength: 'required')]
        public ?OrientationTypeType $orientation = null,
        /** @var StrandTypeType|null strand watson | crick */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/strand-type|5.0.0', strength: 'required')]
        public ?StrandTypeType $strand = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
