<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;

/**
 * @description An exception to the base policy of this consent. An exception can be an addition or removal of access permissions.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision', fhirVersion: 'R4')]
class FHIRConsentProvision extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRConsentProvisionTypeType|null type deny | permit */
        public ?FHIRConsentProvisionTypeType $type = null,
        /** @var FHIRPeriod|null period Timeframe for this rule */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRConsentProvisionActor> actor Who|what controlled by this rule (or group, by role) */
        public array $actor = [],
        /** @var array<FHIRCodeableConcept> action Actions controlled by this rule */
        public array $action = [],
        /** @var array<FHIRCoding> securityLabel Security Labels that define affected resources */
        public array $securityLabel = [],
        /** @var array<FHIRCoding> purpose Context of activities covered by this rule */
        public array $purpose = [],
        /** @var array<FHIRCoding> class e.g. Resource Type, Profile, CDA, etc. */
        public array $class = [],
        /** @var array<FHIRCodeableConcept> code e.g. LOINC or SNOMED CT code, etc. in the content */
        public array $code = [],
        /** @var FHIRPeriod|null dataPeriod Timeframe for data controlled by this rule */
        public ?FHIRPeriod $dataPeriod = null,
        /** @var array<FHIRConsentProvisionData> data Data controlled by this rule */
        public array $data = [],
        /** @var array<FHIRConsentProvision> provision Nested Exception Rules */
        public array $provision = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
