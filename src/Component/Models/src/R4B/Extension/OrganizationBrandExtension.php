<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/organization-brand
 *
 * @description Organization-level Brand information.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/organization-brand', fhirVersion: 'R4B')]
class OrganizationBrandExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var array<UrlPrimitive> brandLogo Brand Logo */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $brandLogo = [],
        /** @var array<Coding> brandLogoLicenseType Brand Logo License Type */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isArray: true)]
        public array $brandLogoLicenseType = [],
        /** @var array<UrlPrimitive> brandLogoLicense Brand Logo License */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $brandLogoLicense = [],
        /** @var array<UrlPrimitive> brandBundle Brand Bundle URL */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
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
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $brandLogo            = [];
        $brandLogoLicenseType = [];
        $brandLogoLicense     = [];
        $brandBundle          = [];

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
