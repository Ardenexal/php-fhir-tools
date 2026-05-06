<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @description Information on how the medication should be stored, for example, refrigeration temperatures and length of stability at a given temperature.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.storageGuideline', fhirVersion: 'R5')]
class MedicationKnowledgeStorageGuideline extends BackboneElement
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
        /** @var UriPrimitive|null reference Reference to additional information */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $reference = null,
        /** @var array<Annotation> note Additional storage notes */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
        /** @var Duration|null stabilityDuration Duration remains stable */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $stabilityDuration = null,
        /** @var array<MedicationKnowledgeStorageGuidelineEnvironmentalSetting> environmentalSetting Setting or value of environment for adequate storage */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MedicationKnowledge\MedicationKnowledgeStorageGuidelineEnvironmentalSetting',
        )]
        public array $environmentalSetting = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
