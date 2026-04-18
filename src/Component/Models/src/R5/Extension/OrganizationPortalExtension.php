<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/organization-portal
 *
 * @description Organization-level portal information.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/organization-portal', fhirVersion: 'R5')]
class OrganizationPortalExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null portalName Portal Name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $portalName = null,
        /** @var MarkdownPrimitive|null portalDescription Portal Description */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $portalDescription = null,
        /** @var UrlPrimitive|null portalUrl Portal URL */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive')]
        public ?UrlPrimitive $portalUrl = null,
        /** @var array<UrlPrimitive> portalLogo Portal Logo */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $portalLogo = [],
        /** @var array<Coding> portalLogoLicenseType Portal Logo License Type */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isArray: true)]
        public array $portalLogoLicenseType = [],
        /** @var array<UrlPrimitive> portalLogoLicense Portal Logo License */
        #[FhirProperty(fhirType: 'url', propertyKind: 'primitive', isArray: true)]
        public array $portalLogoLicense = [],
        /** @var array<Reference> portalEndpoint Endpoint associated with this portal */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $portalEndpoint = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->portalName !== null) {
            $subExtensions[] = new Extension(url: 'portalName', value: $this->portalName);
        }
        if ($this->portalDescription !== null) {
            $subExtensions[] = new Extension(url: 'portalDescription', value: $this->portalDescription);
        }
        if ($this->portalUrl !== null) {
            $subExtensions[] = new Extension(url: 'portalUrl', value: $this->portalUrl);
        }
        foreach ($this->portalLogo as $v) {
            $subExtensions[] = new Extension(url: 'portalLogo', value: $v);
        }
        foreach ($this->portalLogoLicenseType as $v) {
            $subExtensions[] = new Extension(url: 'portalLogoLicenseType', value: $v);
        }
        foreach ($this->portalLogoLicense as $v) {
            $subExtensions[] = new Extension(url: 'portalLogoLicense', value: $v);
        }
        foreach ($this->portalEndpoint as $v) {
            $subExtensions[] = new Extension(url: 'portalEndpoint', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/organization-portal',
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
        $portalName            = null;
        $portalDescription     = null;
        $portalUrl             = null;
        $portalLogo            = [];
        $portalLogoLicenseType = [];
        $portalLogoLicense     = [];
        $portalEndpoint        = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'portalName' && $ext->value instanceof StringPrimitive) {
                $portalName = $ext->value;
            }
            if ($extUrl === 'portalDescription' && $ext->value instanceof MarkdownPrimitive) {
                $portalDescription = $ext->value;
            }
            if ($extUrl === 'portalUrl' && $ext->value instanceof UrlPrimitive) {
                $portalUrl = $ext->value;
            }
            if ($extUrl === 'portalLogo' && $ext->value instanceof UrlPrimitive) {
                $portalLogo[] = $ext->value;
            }
            if ($extUrl === 'portalLogoLicenseType' && $ext->value instanceof Coding) {
                $portalLogoLicenseType[] = $ext->value;
            }
            if ($extUrl === 'portalLogoLicense' && $ext->value instanceof UrlPrimitive) {
                $portalLogoLicense[] = $ext->value;
            }
            if ($extUrl === 'portalEndpoint' && $ext->value instanceof Reference) {
                $portalEndpoint[] = $ext->value;
            }
        }

        return new static($portalName, $portalDescription, $portalUrl, $portalLogo, $portalLogoLicenseType, $portalLogoLicense, $portalEndpoint, $id);
    }
}
