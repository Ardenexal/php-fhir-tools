<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris
 * @description Supports automated discovery of OAuth2 endpoints.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://fhir-registry.smarthealthit.org/StructureDefinition/oauth-uris', fhirVersion: 'R4')]
class OAuthUrisExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var UriPrimitive authorize OAuth2 "authorize" endpoint */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $authorize,
		/** @var UriPrimitive token OAuth2 "token" endpoint */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $token,
		/** @var UriPrimitive|null register OAuth2 dynamic registration endpoint */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $register = null,
		/** @var UriPrimitive|null manage User-facing authorization management entry point */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $manage = null,
		?string $id = null,
	) {
		$subExtensions = [];
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
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$authorize = null;
		$token = null;
		$register = null;
		$manage = null;

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
