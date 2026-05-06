<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\EpisodeOfCare;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\EpisodeOfCareStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The history of statuses that the EpisodeOfCare has been through (without requiring processing the history of the resource).
 */
#[FHIRBackboneElement(parentResource: 'EpisodeOfCare', elementPath: 'EpisodeOfCare.statusHistory', fhirVersion: 'R5')]
class EpisodeOfCareStatusHistory extends BackboneElement
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
        /** @var EpisodeOfCareStatusType|null status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?EpisodeOfCareStatusType $status = null,
        /** @var Period|null period Duration the EpisodeOfCare was in the specified status */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
