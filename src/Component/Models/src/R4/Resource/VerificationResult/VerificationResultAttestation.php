<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Information about the entity attesting to information.
 */
#[FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.attestation', fhirVersion: 'R4')]
class VerificationResultAttestation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null who The individual or organization attesting to information */
        public ?Reference $who = null,
        /** @var Reference|null onBehalfOf When the who is asserting on behalf of another (organization or individual) */
        public ?Reference $onBehalfOf = null,
        /** @var CodeableConcept|null communicationMethod The method by which attested information was submitted/retrieved */
        public ?CodeableConcept $communicationMethod = null,
        /** @var DatePrimitive|null date The date the information was attested to */
        public ?DatePrimitive $date = null,
        /** @var StringPrimitive|string|null sourceIdentityCertificate A digital identity certificate associated with the attestation source */
        public StringPrimitive|string|null $sourceIdentityCertificate = null,
        /** @var StringPrimitive|string|null proxyIdentityCertificate A digital identity certificate associated with the proxy entity submitting attested information on behalf of the attestation source */
        public StringPrimitive|string|null $proxyIdentityCertificate = null,
        /** @var Signature|null proxySignature Proxy signature */
        public ?Signature $proxySignature = null,
        /** @var Signature|null sourceSignature Attester signature */
        public ?Signature $sourceSignature = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
