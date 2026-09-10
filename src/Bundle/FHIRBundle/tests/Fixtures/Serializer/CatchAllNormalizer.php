<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Fixtures\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Stand-in for `ObjectNormalizer` in the application serializer tests.
 *
 * `ObjectNormalizer` is what actually sits at the bottom of a real application's normalizer chain, at
 * priority -1000, claiming every class nothing above it claimed — and mangling FHIR models when it gets
 * one. The registration tests need something in that position to assert the FHIR normalizers land
 * *above* it, but they cannot use the real class: `SerializerPass` binds `array $defaultContext` into
 * every tagged normalizer, and on `ObjectNormalizer` that is the last of seven constructor parameters,
 * so the container refuses the definition unless the six preceding arguments are supplied too — a list
 * that differs between the Symfony versions this bundle supports.
 *
 * This class takes no constructor arguments, so the binding has nothing to fill and the definition
 * compiles identically on 6.4 and 7.4. The tests never instantiate it; they only read its position in
 * the compiled `serializer` definition.
 */
final class CatchAllNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * Produces nothing — the chain position is what the tests read, never the output.
     *
     * @param array<string, mixed> $context
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): null
    {
        return null;
    }

    /**
     * Claims everything, the way `ObjectNormalizer` does.
     *
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return true;
    }

    /**
     * Produces nothing — the chain position is what the tests read, never the output.
     *
     * @param array<string, mixed> $context
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): null
    {
        return null;
    }

    /**
     * Claims everything, the way `ObjectNormalizer` does.
     *
     * @param array<string, mixed> $context
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return true;
    }

    /**
     * Declares the same open-ended, uncacheable support as `ObjectNormalizer`.
     *
     * @param string|null $format serialization format being asked about, ignored here
     *
     * @return array<string, bool|null> every type, with caching refused
     */
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => false, '*' => false];
    }
}
