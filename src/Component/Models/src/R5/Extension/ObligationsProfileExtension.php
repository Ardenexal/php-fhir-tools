<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/obligations-profile
 *
 * @description This extension declares that the data in a resource was authored (collected/handled/created/transformed) by an application that conformed to the set of element level obligations described in the referenced profile.  Note that  'authoring' is often a client function, but that is not always the case. The declaration may optionally reference a particular ActorDefinition to select which set of obligations applied. Note that although the focus of this extension is declaring which obligations applied to the authoring of this resource, obligations are applied at the level of a data element, and the profile maps between the elements of the resource and the obligations  at each data element. For this reason, it's better for profiles referenced by this extension to be  [obligation profiles](https://hl7.org/fhir/tools/StructureDefinition-obligation-profile.html), or at least, profiles  that focus on applicaion obligations rather than data verification. Only the obligations pertaining to authoring the  data are considered to apply in this declaration.
 *
 *   As an example of the kind of use this extension might support, an application could decide, while reading the contents of a resource, that an element was available for data entry and the fact that it is mising implies 'no value exists'  as opposed to 'creating system was unable to provide a value'.   This extension is a statement about the provenance of a particular version of the resource that it is describing; as  such, it should be made in a Provenance resource referring to that particular version. Alternatively, the extension  can be placed in the resource about which the declaration is made (in Resource.meta); in this case, the declaration  SHOULD be removed when the resource data is altered (it may be replaced by a new declaration).
 *
 *   Unlike the claim of data conformance in Resource.meta.profile, the declaration contained in this extension  cannot be added simply because the data in the resource is observed to conform to a profile, since it pertains  to the rules that apply to the application that authored the resource. For the same reason, intermediaries processing resources SHOULD preserve this declaration unless they alter the data itself.
 *
 *   This declaration is often used to drive processing rules associated with the trustworthiness of the data in  the resource. Applications/specifications/workflows that make use of this declaration should carefully consider the integrity of the chain of handling from the source the processing before choosing to trust the declaration.
 *
 *   A simpler alternative to this profile is to use the [[[http://hl7.org/fhir/StructureDefinition/feature-assertion]]] extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/obligations-profile', fhirVersion: 'R5')]
class ObligationsProfileExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CanonicalPrimitive profile The profile linking elements to obligations */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public CanonicalPrimitive $profile,
        /** @var UriPrimitive|null actor Indicates which actor obligations apply. */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $actor = null,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'profile', value: $this->profile);
        if ($this->actor !== null) {
            $subExtensions[] = new Extension(url: 'actor', value: $this->actor);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/obligations-profile',
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
        $profile = null;
        $actor   = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'profile' && $ext->value instanceof CanonicalPrimitive) {
                $profile = $ext->value;
            }
            if ($extUrl === 'actor' && $ext->value instanceof UriPrimitive) {
                $actor = $ext->value;
            }
        }

        if ($profile === null) {
            throw new \InvalidArgumentException('Required sub-extension "profile" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($profile, $actor, $id);
    }
}
