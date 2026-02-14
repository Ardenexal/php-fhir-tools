<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ImmunizationRecommendation\ImmunizationRecommendationRecommendation;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Public Health and Emergency Response)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImmunizationRecommendation
 *
 * @description A patient's point-in-time set of recommendations (i.e. forecasting) according to a published schedule with optional supporting justification.
 */
#[FhirResource(
    type: 'ImmunizationRecommendation',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ImmunizationRecommendation',
    fhirVersion: 'R4',
)]
class ImmunizationRecommendationResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var Reference|null patient Who this profile is for */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var DateTimePrimitive|null date Date recommendation(s) created */
        #[NotBlank]
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null authority Who is responsible for protocol */
        public ?Reference $authority = null,
        /** @var array<ImmunizationRecommendationRecommendation> recommendation Vaccine administration recommendations */
        public array $recommendation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
