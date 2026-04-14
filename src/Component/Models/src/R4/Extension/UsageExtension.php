<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-usage
 *
 * @description Consumers of the value set and the implementations, projects or standards that the author has utilized the value set in.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-usage', fhirVersion: 'R4')]
class UsageExtension extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive user A consumer of or client for the value set */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $user,
        /** @var StringPrimitive use Implementation/project/standard that uses value set */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive $use,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'user', value: $this->user);
        $subExtensions[] = new Extension(url: 'use', value: $this->use);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-usage',
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
        $user = null;
        $use  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'user' && $ext->value instanceof StringPrimitive) {
                $user = $ext->value;
            }
            if ($extUrl === 'use' && $ext->value instanceof StringPrimitive) {
                $use = $ext->value;
            }
        }

        return new static($user, $use, $id);
    }
}
