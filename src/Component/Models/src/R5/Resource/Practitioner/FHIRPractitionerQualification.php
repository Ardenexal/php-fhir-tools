<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The official qualifications, certifications, accreditations, training, licenses (and other types of educations/skills/capabilities) that authorize or otherwise pertain to the provision of care by the practitioner.
 *
 * For example, a medical license issued by a medical board of licensure authorizing the practitioner to practice medicine within a certain locality.
 */
#[FHIRBackboneElement(parentResource: 'Practitioner', elementPath: 'Practitioner.qualification', fhirVersion: 'R5')]
class FHIRPractitionerQualification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier An identifier for this qualification for the practitioner */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null code Coded representation of the qualification */
        #[NotBlank]
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRPeriod|null period Period during which the qualification is valid */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null issuer Organization that regulates and issues the qualification */
        public ?FHIRReference $issuer = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
