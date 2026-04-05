<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.protocolApplied', fhirVersion: 'R4')]
class ImmunizationProtocolApplied extends BackboneElement
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
        /** @var StringPrimitive|string|null series Name of vaccine series */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $series = null,
        /** @var Reference|null authority Who is responsible for publishing the recommendations */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $authority = null,
        /** @var array<CodeableConcept> targetDisease Vaccine preventatable disease being targetted */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $targetDisease = [],
        /** @var PositiveIntPrimitive|StringPrimitive|string|null doseNumber Dose number within series */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isRequired: true,
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'doseNumberPositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'doseNumberString',
                ],
            ],
        )]
        #[NotBlank]
        public PositiveIntPrimitive|StringPrimitive|string|null $doseNumber = null,
        /** @var PositiveIntPrimitive|StringPrimitive|string|null seriesDoses Recommended number of doses for immunity */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'positiveInt',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive',
                    'jsonKey'      => 'seriesDosesPositiveInt',
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'seriesDosesString',
                ],
            ],
        )]
        public PositiveIntPrimitive|StringPrimitive|string|null $seriesDoses = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
