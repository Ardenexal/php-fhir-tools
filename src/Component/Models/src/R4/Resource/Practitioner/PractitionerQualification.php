<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Practitioner;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The official certifications, training, and licenses that authorize or otherwise pertain to the provision of care by the practitioner.  For example, a medical license issued by a medical board authorizing the practitioner to practice medicine within a certian locality.
 */
#[FHIRBackboneElement(parentResource: 'Practitioner', elementPath: 'Practitioner.qualification', fhirVersion: 'R4')]
class PractitionerQualification extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier An identifier for this qualification for the practitioner */
        public array $identifier = [],
        /** @var CodeableConcept|null code Coded representation of the qualification */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Period|null period Period during which the qualification is valid */
        public ?Period $period = null,
        /** @var Reference|null issuer Organization that regulates and issues the qualification */
        public ?Reference $issuer = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
