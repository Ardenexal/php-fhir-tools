<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/package-source
 * @description Specifies the package in which an artifact is or was included.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/package-source', fhirVersion: 'R4')]
class PackageSourceExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var IdPrimitive packageId NPM-style package id */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $packageId,
		/** @var StringPrimitive|null version Package version */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $version = null,
		/** @var UriPrimitive|null uri Package uri */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $uri = null,
		?string $id = null,
	) {
		$subExtensions = [];
		$subExtensions[] = new Extension(url: 'packageId', value: $this->packageId);
		if ($this->version !== null) {
		    $subExtensions[] = new Extension(url: 'version', value: $this->version);
		}
		if ($this->uri !== null) {
		    $subExtensions[] = new Extension(url: 'uri', value: $this->uri);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/package-source',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$packageId = null;
		$version = null;
		$uri = null;

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'packageId' && $ext->value instanceof IdPrimitive) {
		        $packageId = $ext->value;
		    }
		    if ($extUrl === 'version' && $ext->value instanceof StringPrimitive) {
		        $version = $ext->value;
		    }
		    if ($extUrl === 'uri' && $ext->value instanceof UriPrimitive) {
		        $uri = $ext->value;
		    }
		}

		return new static($packageId, $version, $uri, $id);
	}
}
