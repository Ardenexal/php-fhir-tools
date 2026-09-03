<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

/**
 * Locates generated model classes by FHIR type name, using the layout the generator emits.
 *
 * @author Ardenexal
 */
final class FHIRModelClassLocator implements FHIRModelClassLocatorInterface
{
    /**
     * Versions searched, in order, when the caller supplies none.
     *
     * R4 first is not a preference so much as the established answer: the `get_declared_classes()`
     * scan this replaces returned whichever version was loaded, and the serialized-type resolver's
     * own unscoped fallback already documents R4-first. Keeping the same order keeps the unscoped
     * path answering what it answered before.
     */
    private const array VERSIONS = ['R4', 'R4B', 'R5'];

    private const string MODEL_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models\\';

    /**
     * How each structure kind spells a type name as a class, as `[sub-namespace, suffix]`.
     *
     * Backbone elements and logical models are absent deliberately: a backbone element's FHIR type
     * name is a dotted path (`Substance.ingredient`) that no PHP class name can carry, so there is
     * nothing to look up by name. Asking for those kinds answers null rather than guessing.
     *
     * @var array<string, array{string, string}>
     */
    private const array LAYOUT = [
        FHIRStructureKind::Resource->value      => ['Resource', 'Resource'],
        FHIRStructureKind::ComplexType->value   => ['DataType', ''],
        FHIRStructureKind::PrimitiveType->value => ['Primitive', 'Primitive'],
    ];

    /** @var array<string, class-string|false> */
    private array $cache = [];

    /**
     * {@inheritDoc}
     */
    public function locate(string $fhirTypeName, ?string $fhirVersion = null, FHIRStructureKind ...$kinds): ?string
    {
        if ($fhirTypeName === '' || str_contains($fhirTypeName, '\\')) {
            return null;
        }

        $wanted = $kinds === []
            ? array_keys(self::LAYOUT)
            : array_values(array_filter(
                array_map(static fn (FHIRStructureKind $kind): string => $kind->value, $kinds),
                static fn (string $kind): bool => isset(self::LAYOUT[$kind]),
            ));

        $fhirVersion = self::normalizeVersion($fhirVersion);

        $key = $fhirTypeName . '|' . ($fhirVersion ?? '*') . '|' . implode(',', $wanted);

        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->search($fhirTypeName, $fhirVersion, $wanted) ?? false;
        }

        return $this->cache[$key] ?: null;
    }

    /**
     * Canonicalise a caller-supplied version, rejecting anything this locator cannot place.
     *
     * A named version scopes the search strictly, so an unrecognised string matches no namespace and
     * every lookup answers null — which reads downstream as "this type has no ancestors" rather than
     * "you passed a version that does not exist". That is the wrong failure: conformance answers
     * silently flip to false and nothing reports why. FHIR versions are also commonly written as
     * spec numbers (`4.0.1`, `5.0.0`) — a shape this project itself uses when reading packages — so
     * the mistake is an easy one to make from a public entry point.
     *
     * Case is folded rather than rejected: PHP resolves namespaces case-insensitively, so `r4`
     * already located the same classes as `R4`. Folding keeps that working and makes it deliberate.
     *
     * @param string|null $fhirVersion Caller-supplied version, or null for the unscoped search
     *
     * @return string|null The canonical spelling, or null when the caller supplied none
     *
     * @throws \InvalidArgumentException when the version is not one this locator can place
     */
    private static function normalizeVersion(?string $fhirVersion): ?string
    {
        if ($fhirVersion === null) {
            return null;
        }

        foreach (self::VERSIONS as $known) {
            if (strcasecmp($fhirVersion, $known) === 0) {
                return $known;
            }
        }

        throw new \InvalidArgumentException(sprintf('Unknown FHIR version "%s"; expected one of %s. Spec version numbers such as "4.0.1" are not accepted here — pass the release label instead.', $fhirVersion, implode(', ', self::VERSIONS)));
    }

    /**
     * @param list<string> $kinds
     *
     * @return class-string|null
     */
    private function search(string $fhirTypeName, ?string $fhirVersion, array $kinds): ?string
    {
        // A named version scopes the search strictly. Answering from another release would hand back
        // an object of the wrong version, which is worse than reporting that nothing matched.
        $versions = $fhirVersion !== null ? [$fhirVersion] : self::VERSIONS;

        foreach ($versions as $version) {
            foreach ($kinds as $kind) {
                [$subNamespace, $suffix] = self::LAYOUT[$kind];

                /** @var class-string $candidate */
                $candidate = self::MODEL_NAMESPACE . $version . '\\' . $subNamespace . '\\'
                    . ucfirst($fhirTypeName) . $suffix;

                if (class_exists($candidate)) {
                    return $candidate;
                }
            }
        }

        return null;
    }
}
