<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 * @see http://hl7.org/fhir/StructureDefinition/organization-brand
 * @description Organization-level Brand information.
 */
#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/organization-brand', fhirVersion: 'R4')]
class OrganizationBrandExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements \Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface
{
	public function __construct(
		/** @var array<UrlPrimitive> brandLogo Brand Logo */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
		public array $brandLogo = [],
		/** @var array<Coding> brandLogoLicenseType Brand Logo License Type */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isArray: true)]
		public array $brandLogoLicenseType = [],
		/** @var array<UrlPrimitive> brandLogoLicense Brand Logo License */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
		public array $brandLogoLicense = [],
		/** @var array<UrlPrimitive> brandBundle Brand Bundle URL */
		#[\Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
		public array $brandBundle = [],
		?string $id = null,
	) {
		$subExtensions = [];
		foreach ($this->brandLogo as $v) {
		    $subExtensions[] = new Extension(url: 'brandLogo', value: $v);
		}
		foreach ($this->brandLogoLicenseType as $v) {
		    $subExtensions[] = new Extension(url: 'brandLogoLicenseType', value: $v);
		}
		foreach ($this->brandLogoLicense as $v) {
		    $subExtensions[] = new Extension(url: 'brandLogoLicense', value: $v);
		}
		foreach ($this->brandBundle as $v) {
		    $subExtensions[] = new Extension(url: 'brandBundle', value: $v);
		}
		parent::__construct(
		    id: $id,
		    extension: $subExtensions,
		    url: 'http://hl7.org/fhir/StructureDefinition/organization-brand',
		);
	}


	/**
	 * Reconstruct from an array of already-denormalized sub-extension objects.
	 * @param array<\Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface> $subExtensions
	 * @param string|null $id
	 */
	public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
	{
		$brandLogo = [];
		$brandLogoLicenseType = [];
		$brandLogoLicense = [];
		$brandBundle = [];

		foreach ($subExtensions as $ext) {
		    $extUrl = $ext->getExtensionUrl();
		    if ($extUrl === 'brandLogo' && $ext->value instanceof UrlPrimitive) {
		        $brandLogo[] = $ext->value;
		    }
		    if ($extUrl === 'brandLogoLicenseType' && $ext->value instanceof Coding) {
		        $brandLogoLicenseType[] = $ext->value;
		    }
		    if ($extUrl === 'brandLogoLicense' && $ext->value instanceof UrlPrimitive) {
		        $brandLogoLicense[] = $ext->value;
		    }
		    if ($extUrl === 'brandBundle' && $ext->value instanceof UrlPrimitive) {
		        $brandBundle[] = $ext->value;
		    }
		}

		return new static($brandLogo, $brandLogoLicenseType, $brandLogoLicense, $brandBundle, $id);
	}
}
