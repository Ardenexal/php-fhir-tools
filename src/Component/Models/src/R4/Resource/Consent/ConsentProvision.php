<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ConsentProvisionTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;

/**
 * @description An exception to the base policy of this consent. An exception can be an addition or removal of access permissions.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision', fhirVersion: 'R4')]
class ConsentProvision extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var ConsentProvisionTypeType|null type deny | permit */
        public ?ConsentProvisionTypeType $type = null,
        /** @var Period|null period Timeframe for this rule */
        public ?Period $period = null,
        /** @var array<ConsentProvisionActor> actor Who|what controlled by this rule (or group, by role) */
        public array $actor = [],
        /** @var array<CodeableConcept> action Actions controlled by this rule */
        public array $action = [],
        /** @var array<Coding> securityLabel Security Labels that define affected resources */
        public array $securityLabel = [],
        /** @var array<Coding> purpose Context of activities covered by this rule */
        public array $purpose = [],
        /** @var array<Coding> class e.g. Resource Type, Profile, CDA, etc. */
        public array $class = [],
        /** @var array<CodeableConcept> code e.g. LOINC or SNOMED CT code, etc. in the content */
        public array $code = [],
        /** @var Period|null dataPeriod Timeframe for data controlled by this rule */
        public ?Period $dataPeriod = null,
        /** @var array<ConsentProvisionData> data Data controlled by this rule */
        public array $data = [],
        /** @var array<ConsentProvision> provision Nested Exception Rules */
        public array $provision = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
