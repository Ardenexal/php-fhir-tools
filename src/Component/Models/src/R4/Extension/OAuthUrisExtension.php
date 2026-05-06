<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris
 *
 * @description Supports automated discovery of OAuth2 endpoints.
 */
#[FHIRExtensionDefinition(url: 'http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris', fhirVersion: 'R4')]
class OAuthUrisExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var UriPrimitive authorize OAuth2 "authorize" endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public UriPrimitive $authorize,
        /** @var UriPrimitive token OAuth2 "token" endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public UriPrimitive $token,
        /** @var UriPrimitive|null register OAuth2 dynamic registration endpoint */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $register = null,
        /** @var UriPrimitive|null introspect User-facing introspection end point */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $introspect = null,
        /** @var UriPrimitive|null revoke User-facing revocation end point */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $revoke = null,
        /** @var UriPrimitive|null manage User-facing authorization management entry point */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $manage = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'authorize', value: $this->authorize);
        $subExtensions[] = new Extension(url: 'token', value: $this->token);
        if ($this->register !== null) {
            $subExtensions[] = new Extension(url: 'register', value: $this->register);
        }
        if ($this->introspect !== null) {
            $subExtensions[] = new Extension(url: 'introspect', value: $this->introspect);
        }
        if ($this->revoke !== null) {
            $subExtensions[] = new Extension(url: 'revoke', value: $this->revoke);
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
        $authorize  = null;
        $token      = null;
        $register   = null;
        $introspect = null;
        $revoke     = null;
        $manage     = null;

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
            if ($extUrl === 'introspect' && $ext->value instanceof UriPrimitive) {
                $introspect = $ext->value;
            }
            if ($extUrl === 'revoke' && $ext->value instanceof UriPrimitive) {
                $revoke = $ext->value;
            }
            if ($extUrl === 'manage' && $ext->value instanceof UriPrimitive) {
                $manage = $ext->value;
            }
        }

        if ($authorize === null) {
            throw new \InvalidArgumentException('Required sub-extension "authorize" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($token === null) {
            throw new \InvalidArgumentException('Required sub-extension "token" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($authorize, $token, $register, $introspect, $revoke, $manage, $id);
    }
}
