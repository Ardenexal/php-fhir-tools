<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConditionalDeleteStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRConditionalReadStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReferenceHandlingPolicyType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceVersionPolicyType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A specification of the restful capabilities of the solution for a specific resource type.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.resource', fhirVersion: 'R4B')]
class FHIRCapabilityStatementRestResource extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRResourceTypeType|null type A resource type that is supported */
        #[NotBlank]
        public ?FHIRResourceTypeType $type = null,
        /** @var FHIRCanonical|null profile Base System profile for all uses of resource */
        public ?FHIRCanonical $profile = null,
        /** @var array<FHIRCanonical> supportedProfile Profiles for use cases supported */
        public array $supportedProfile = [],
        /** @var FHIRMarkdown|null documentation Additional information about the use of the resource type */
        public ?FHIRMarkdown $documentation = null,
        /** @var array<FHIRCapabilityStatementRestResourceInteraction> interaction What operations are supported? */
        public array $interaction = [],
        /** @var FHIRResourceVersionPolicyType|null versioning no-version | versioned | versioned-update */
        public ?FHIRResourceVersionPolicyType $versioning = null,
        /** @var FHIRBoolean|null readHistory Whether vRead can return past versions */
        public ?FHIRBoolean $readHistory = null,
        /** @var FHIRBoolean|null updateCreate If update can commit to a new identity */
        public ?FHIRBoolean $updateCreate = null,
        /** @var FHIRBoolean|null conditionalCreate If allows/uses conditional create */
        public ?FHIRBoolean $conditionalCreate = null,
        /** @var FHIRConditionalReadStatusType|null conditionalRead not-supported | modified-since | not-match | full-support */
        public ?FHIRConditionalReadStatusType $conditionalRead = null,
        /** @var FHIRBoolean|null conditionalUpdate If allows/uses conditional update */
        public ?FHIRBoolean $conditionalUpdate = null,
        /** @var FHIRConditionalDeleteStatusType|null conditionalDelete not-supported | single | multiple - how conditional delete is supported */
        public ?FHIRConditionalDeleteStatusType $conditionalDelete = null,
        /** @var array<FHIRReferenceHandlingPolicyType> referencePolicy literal | logical | resolves | enforced | local */
        public array $referencePolicy = [],
        /** @var array<FHIRString|string> searchInclude _include values supported by the server */
        public array $searchInclude = [],
        /** @var array<FHIRString|string> searchRevInclude _revinclude values supported by the server */
        public array $searchRevInclude = [],
        /** @var array<FHIRCapabilityStatementRestResourceSearchParam> searchParam Search parameters supported by implementation */
        public array $searchParam = [],
        /** @var array<FHIRCapabilityStatementRestResourceOperation> operation Definition of a resource operation */
        public array $operation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
