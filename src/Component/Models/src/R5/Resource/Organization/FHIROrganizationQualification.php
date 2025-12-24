<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The official certifications, accreditations, training, designations and licenses that authorize and/or otherwise endorse the provision of care by the organization.
 *
 * For example, an approval to provide a type of services issued by a certifying body (such as the US Joint Commission) to an organization.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Organization', elementPath: 'Organization.qualification', fhirVersion: 'R5')]
class FHIROrganizationQualification extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier An identifier for this qualification for the organization */
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
