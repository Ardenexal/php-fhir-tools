<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;

/**
 * @description An exception to the base policy of this consent. An exception can be an addition or removal of access permissions.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision', fhirVersion: 'R5')]
class FHIRConsentProvision extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPeriod|null period Timeframe for this provision */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRConsentProvisionActor> actor Who|what controlled by this provision (or group, by role) */
        public array $actor = [],
        /** @var array<FHIRCodeableConcept> action Actions controlled by this provision */
        public array $action = [],
        /** @var array<FHIRCoding> securityLabel Security Labels that define affected resources */
        public array $securityLabel = [],
        /** @var array<FHIRCoding> purpose Context of activities covered by this provision */
        public array $purpose = [],
        /** @var array<FHIRCoding> documentType e.g. Resource Type, Profile, CDA, etc */
        public array $documentType = [],
        /** @var array<FHIRCoding> resourceType e.g. Resource Type, Profile, etc */
        public array $resourceType = [],
        /** @var array<FHIRCodeableConcept> code e.g. LOINC or SNOMED CT code, etc. in the content */
        public array $code = [],
        /** @var FHIRPeriod|null dataPeriod Timeframe for data controlled by this provision */
        public ?FHIRPeriod $dataPeriod = null,
        /** @var array<FHIRConsentProvisionData> data Data controlled by this provision */
        public array $data = [],
        /** @var FHIRExpression|null expression A computable expression of the consent */
        public ?FHIRExpression $expression = null,
        /** @var array<FHIRConsentProvision> provision Nested Exception Provisions */
        public array $provision = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
