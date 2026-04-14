<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris
 *
 * @description Supports automated discovery of OAuth2 endpoints.
 */
#[FHIRExtensionDefinition(url: 'http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris', fhirVersion: 'R4B')]
class OauthUrisExtension extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UriPrimitive authorize OAuth2 "authorize" endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $authorize,
        /** @var UriPrimitive token OAuth2 "token" endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $token,
        /** @var UriPrimitive|null register OAuth2 dynamic registration endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $register = null,
        /** @var UriPrimitive|null manage User-facing authorization management entry point */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive $manage = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'authorize', value: $this->authorize);
        $subExtensions[] = new Extension(url: 'token', value: $this->token);
        if ($this->register !== null) {
            $subExtensions[] = new Extension(url: 'register', value: $this->register);
        }
        if ($this->manage !== null) {
            $subExtensions[] = new Extension(url: 'manage', value: $this->manage);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $authorize = null;
        $token     = null;
        $register  = null;
        $manage    = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'authorize' && $ext->value instanceof UriPrimitive) {
                $authorize = $ext->value;
            }
            if ($extUrl === 'token' && $ext->value instanceof UriPrimitive) {
                $token = $ext->value;
            }
            if ($extUrl === 'register' && $ext->value instanceof UriPrimitive) {
                $register = $ext->value;
            }
            if ($extUrl === 'manage' && $ext->value instanceof UriPrimitive) {
                $manage = $ext->value;
            }
        }

        return new static($authorize, $token, $register, $manage, $id);
    }
}
