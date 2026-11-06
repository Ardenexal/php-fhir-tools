<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * Which sub-extensions a typed complex extension did not take.
 *
 * A typed complex extension's constructor rebuilds its `extension` list from the slices it
 * declares, so anything else in the document was silently dropped: `species-x` in
 * `patient-extension-complex-bad2`, or a `bodySite` extension nested in `patient-animal`. The
 * reference validator keeps both, and reports the first as undefined and the second where it is
 * not allowed. The deserializer uses this to keep what the typed class did not take, and
 * validation uses it to tell the two apart again.
 */
final class ComplexExtensionSubExtensions
{
    /**
     * The sub-extensions in $read that $declared does not account for.
     *
     * A sub-extension is accounted for by a declared one with the same url holding the same
     * value: the constructor wraps each value it takes in a new `Extension`, but keeps the value
     * itself. Each declared entry accounts for one read entry at most.
     *
     * @param list<FHIRExtensionInterface> $read     the sub-extensions as read from the document
     * @param list<FHIRExtensionInterface> $declared the typed class's own `extension` list
     *
     * @return list<FHIRExtensionInterface> the read sub-extensions left over, in document order
     */
    public static function undeclared(array $read, array $declared): array
    {
        $unmatched = $declared;
        $leftOver  = [];

        foreach ($read as $subExtension) {
            foreach ($unmatched as $index => $candidate) {
                if ($candidate->getExtensionUrl() === $subExtension->getExtensionUrl()
                    && self::valueOf($candidate)  === self::valueOf($subExtension)
                ) {
                    unset($unmatched[$index]);

                    continue 2;
                }
            }

            $leftOver[] = $subExtension;
        }

        return $leftOver;
    }

    /**
     * Is this url one a complex extension's own slices use?
     *
     * Slices of a complex extension are named by a bare (relative) url such as `species`. An
     * absolute url names another extension entirely, which the open slicing of
     * `Extension.extension` allows alongside the declared slices.
     *
     * @param string|null $url the sub-extension's url
     *
     * @return bool true for a relative url
     */
    public static function isSliceUrl(?string $url): bool
    {
        return $url !== null && $url !== '' && preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*:#', $url) !== 1;
    }

    /**
     * Does the typed class declare a slice with this url?
     *
     * The generated class names each constructor parameter after its slice: the slice name
     * camel-cased, with `Slice` appended where it would collide with an `Extension` property
     * (FHIRExtensionGenerator::resolveSliceParamName). Comparing with case and punctuation removed
     * reverses that. A declared slice can still be left over when its value has a type the class
     * did not model, since the generator types each slice by its first allowed type only.
     *
     * @param class-string $class the typed complex extension class
     * @param string       $url   the sub-extension's url
     *
     * @return bool true when a constructor parameter corresponds to the slice
     */
    public static function declaresSlice(string $class, string $url): bool
    {
        $constructor = (new \ReflectionClass($class))->getConstructor();

        if ($constructor === null) {
            return false;
        }

        $wanted = self::comparable($url);

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (self::comparable($name) === $wanted
                || (str_ends_with($name, 'Slice') && self::comparable(substr($name, 0, -5)) === $wanted)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name a slice or parameter name
     *
     * @return string the name lower-cased with everything but letters and digits removed
     */
    private static function comparable(string $name): string
    {
        return strtolower((string) preg_replace('/[^A-Za-z0-9]/', '', $name));
    }

    /**
     * @param FHIRExtensionInterface $extension a sub-extension
     *
     * @return mixed its `value`, or null when it has none
     */
    private static function valueOf(FHIRExtensionInterface $extension): mixed
    {
        return property_exists($extension, 'value') ? ($extension->value ?? null) : null;
    }
}
