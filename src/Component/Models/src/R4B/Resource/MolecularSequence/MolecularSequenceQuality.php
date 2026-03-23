<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\QualityTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An experimental feature attribute that defines the quality of the feature in a quantitative way, such as a phred quality score ([SO:0001686](http://www.sequenceontology.org/browser/current_svn/term/SO:0001686)).
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality', fhirVersion: 'R4B')]
class MolecularSequenceQuality extends BackboneElement
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
        /** @var QualityTypeType|null type indel | snp | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?QualityTypeType $type = null,
        /** @var CodeableConcept|null standardSequence Standard sequence for comparison */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $standardSequence = null,
        /** @var int|null start Start position of the sequence */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $start = null,
        /** @var int|null end End position of the sequence */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $end = null,
        /** @var Quantity|null score Quality score for the comparison */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $score = null,
        /** @var CodeableConcept|null method Method to get quality */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $method = null,
        /** @var numeric-string|null truthTP True positives from the perspective of the truth data */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $truthTP = null,
        /** @var numeric-string|null queryTP True positives from the perspective of the query data */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $queryTP = null,
        /** @var numeric-string|null truthFN False negatives */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $truthFN = null,
        /** @var numeric-string|null queryFP False positives */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $queryFP = null,
        /** @var numeric-string|null gtFP False positives where the non-REF alleles in the Truth and Query Call Sets match */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $gtFP = null,
        /** @var numeric-string|null precision Precision of comparison */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $precision = null,
        /** @var numeric-string|null recall Recall of comparison */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $recall = null,
        /** @var numeric-string|null fScore F-score */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $fScore = null,
        /** @var MolecularSequenceQualityRoc|null roc Receiver Operator Characteristic (ROC) Curve */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MolecularSequenceQualityRoc $roc = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
