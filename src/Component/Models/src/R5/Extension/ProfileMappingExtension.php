<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/profile-mapping
 *
 * @description Extension definition for Mapping between ```API``` and ```Profile```. CapabilityStatement.rest.resource.profile has two different profile statements:
 *
 * * profile - the overall system profile for a resource
 * * supportedProfile 0..* - a particular profile that the system supports
 *
 * What is missing from this picture is defining which resources conform to
 * which profile. Sometimes, of course, it's impossible to define this, but
 * quite often, there's an algorithmic relationship that a server or client
 * could use to know that resources with a particular set of values will or should
 * conform to a given profile.
 *
 * This extension defines a relationship between a search string and a profile.
 * Any resource that meets a given search string will be expected to conform to the particular profile. If more than one profile is nominated for a search string, resources SHALL conform to all the listed profiles. The last extension can be without a search matches and remaining resources
 *
 * This extension repeats, and order is important - once a match is found, this profile is the one that applies. Todo: If this extension is useful, it will be moved to the core extensions pack
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/profile-mapping', fhirVersion: 'R5')]
class ProfileMappingExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive search The search string for this mapping */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $search,
        /** @var array<CanonicalPrimitive> profile The profile that must match this search string */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $profile = [],
        /** @var MarkdownPrimitive|null documentation Documentation about this particular profile-mapping */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $documentation = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'search', value: $this->search);
        foreach ($this->profile as $v) {
            $subExtensions[] = new Extension(url: 'profile', value: $v);
        }
        if ($this->documentation !== null) {
            $subExtensions[] = new Extension(url: 'documentation', value: $this->documentation);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/profile-mapping',
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
        $search        = null;
        $profile       = [];
        $documentation = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'search' && $ext->value instanceof StringPrimitive) {
                $search = $ext->value;
            }
            if ($extUrl === 'profile' && $ext->value instanceof CanonicalPrimitive) {
                $profile[] = $ext->value;
            }
            if ($extUrl === 'documentation' && $ext->value instanceof MarkdownPrimitive) {
                $documentation = $ext->value;
            }
        }

        if ($search === null) {
            throw new \InvalidArgumentException('Required sub-extension "search" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($search, $profile, $documentation, $id);
    }
}
