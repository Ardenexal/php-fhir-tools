<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/alternate-hash
 *
 * @description An attachment hash using a different integrity check algorithm from Attachment.hash which uses SHA-1
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/alternate-hash', fhirVersion: 'R5')]
class AlternateHashExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Coding algorithm The algorthm */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public Coding $algorithm,
        /** @var StringPrimitive hash The value of the hash */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $hash,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'algorithm', value: $this->algorithm);
        $subExtensions[] = new Extension(url: 'hash', value: $this->hash);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/alternate-hash',
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
        $algorithm = null;
        $hash      = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'algorithm' && $ext->value instanceof Coding) {
                $algorithm = $ext->value;
            }
            if ($extUrl === 'hash' && $ext->value instanceof StringPrimitive) {
                $hash = $ext->value;
            }
        }

        if ($algorithm === null) {
            throw new \InvalidArgumentException('Required sub-extension "algorithm" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($hash === null) {
            throw new \InvalidArgumentException('Required sub-extension "hash" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($algorithm, $hash, $id);
    }
}
